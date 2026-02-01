<script setup lang="ts" generic="T extends Record<string, any>">
import type { BulkAction, TableAction } from '../Types/table';
import { useTableActions } from '../Composables/useTableActions';
import { useTablePagination } from '../Composables/useTablePagination';
import { useTableSearch } from '../Composables/useTableSearch';
import { useTableSelection } from '../Composables/useTableSelection';

export type { BulkAction, TableAction };

type LoadingAnimation = 'carousel' | 'carousel-inverse' | 'elastic' | 'swing';

type Props = {
  table: Table<T>;
  actions?: TableAction<T>[];
  bulkActions?: BulkAction<T>[];
  columns?: ColumnDef<T>[];
  emptyIcon?: string;
  emptyText?: string;
  hiddenColumns?: string[];
  loadingAnimation?: LoadingAnimation;
  resourceName?: string;
  searchable?: boolean;
  selectable?: boolean;
  stickyHeader?: boolean;
};

const props = withDefaults(defineProps<Props>(), {
  emptyIcon: 'i-heroicons-circle-stack',
  emptyText: 'No data available',
  hiddenColumns: () => [],
  loadingAnimation: 'carousel',
  maxHeight: undefined,
  searchable: true,
  selectable: false,
  stickyHeader: false,
});

defineSlots<{
  // UTable cell slots receive row and column props, other slots may not
  [key: string]: (props?: {
    row?: {
      original: T;
      index: number;
    };
    column?: ColumnDef<T>;
  }) => unknown;
}>();

// Hybridly table integration
const datatable = useTable(props, 'table');

// Composables for logic extraction
const { hasSearchFilter, search } = useTableSearch(datatable);
const { goToPage, perPageItems } = useTablePagination(datatable);
const { clearSelection, handleRowSelection, rowSelection, selectedRows } = useTableSelection<T>(() => datatable.data);
const {
  confirmModal,
  contextMenuItems,
  executeConfirmedAction,
  getRowActions,
  onContextMenu,
  pendingAction,
} = useTableActions<T>(props.actions, props.resourceName);

// Loading state from Hybridly router events
const isLoading = useHybridlyLoading();

// Resolve components for h() render functions (must be in setup context)
const UButton = resolveComponent('UButton');
const UCheckbox = resolveComponent('UCheckbox');
const UDropdownMenu = resolveComponent('UDropdownMenu');

// Column visibility state
const columnVisibility = ref<Record<string, boolean>>(
  Object.fromEntries([
    ...datatable.columns.map(col => [String(col.name), true]),
    ...props.hiddenColumns.map(col => [col, false]),
  ]),
);

// Column visibility dropdown items
const visibilityItems = computed(() =>
  datatable.columns.map(col => ({
    icon: columnVisibility.value[String(col.name)] ? 'i-heroicons-check' : undefined,
    label: col.label,
    onSelect: () => {
      const colName = String(col.name);
      columnVisibility.value = {
        ...columnVisibility.value,
        [colName]: !columnVisibility.value[colName],
      };
    },
  })),
);

// Auto-generate columns from Hybridly (must be in component for resolveComponent)
const generatedColumns = computed<ColumnDef<T>[]>(() => {
  const cols: ColumnDef<T>[] = [];

  // Selection checkbox column
  if (props.selectable) {
    cols.push({
      cell: ({ row }: { row: any }) => h(UCheckbox, {
        modelValue: row.getIsSelected(),
        onClick: (event: MouseEvent) => {
          event.preventDefault();
          handleRowSelection(row.index, !row.getIsSelected(), event);
        },
      }),
      header: ({ table }: { table: any }) => h(UCheckbox, {
        'indeterminate': table.getIsSomePageRowsSelected(),
        'modelValue': table.getIsAllPageRowsSelected(),
        'onUpdate:modelValue': (value: boolean) => table.toggleAllPageRowsSelected(!!value),
      }),
      id: 'select',
      meta: { class: { td: 'w-[50px]' } },
    } as ColumnDef<T>);
  }

  // Data columns from Hybridly
  datatable.columns.forEach((column) => {
    const isSortable = column.isSortable ?? false;
    const colName = String(column.name);

    cols.push({
      accessorKey: colName,
      enableSorting: isSortable,
      header: isSortable
        ? () => h(UButton, {
            'class': '-mx-2.5',
            'color': 'neutral',
            'label': column.label,
            'onClick': () => column.toggleSort({
              direction: column.isSorting('asc') ? 'desc' : 'asc',
            }),
            'trailing-icon': column.isSorting('asc')
              ? 'i-heroicons-bars-arrow-up'
              : column.isSorting('desc')
                ? 'i-heroicons-bars-arrow-down'
                : undefined,
            'variant': 'ghost',
          })
        : column.label,
    } as ColumnDef<T>);
  });

  // Actions column
  if (props.actions?.length) {
    cols.push({
      cell: ({ row }: { row: any }) => h(UDropdownMenu, {
        items: getRowActions(row.original),
      }, () => h(UButton, {
        color: 'neutral',
        icon: 'i-heroicons-ellipsis-vertical',
        variant: 'ghost',
      })),
      header: '',
      id: 'actions',
      meta: { class: { td: 'w-[50px]' } },
    } as ColumnDef<T>);
  }

  return cols;
});

// Use custom columns if provided
const tableColumns = computed(() => props.columns ?? generatedColumns.value);
</script>

<template>
  <div class="flex flex-col gap-4">
    <!-- Header: Search + Actions -->
    <div class="flex items-center justify-between gap-4">
      <div v-if="searchable && hasSearchFilter" class="max-w-sm">
        <UInput
          v-model="search"
          icon="i-heroicons-magnifying-glass"
          placeholder="Search..."
        />
      </div>
      <div v-else />

      <div class="flex items-center gap-2">
        <UDropdownMenu :items="visibilityItems">
          <UButton
            color="neutral"
            icon="i-heroicons-view-columns"
            variant="outline"
          />
        </UDropdownMenu>
        <slot name="create" />
      </div>
    </div>

    <!-- Bulk action bar -->
    <div
      v-if="selectable && selectedRows.length > 0"
      class="flex items-center gap-4 rounded-lg bg-primary/10 p-4"
    >
      <span class="text-sm font-medium">
        {{ selectedRows.length }} row(s) selected
      </span>
      <div class="flex gap-2">
        <UButton
          v-for="action in bulkActions"
          :key="action.label"
          :color="action.color ?? 'primary'"
          :icon="action.icon"
          :label="action.label"
          size="sm"
          @click="action.onSelect(selectedRows)"
        />
      </div>
      <UButton
        class="ml-auto"
        color="neutral"
        icon="i-heroicons-x-mark"
        size="sm"
        variant="ghost"
        @click="clearSelection"
      />
    </div>

    <!-- Table -->
    <UContextMenu :items="contextMenuItems">
      <UTable
        v-model:column-visibility="columnVisibility"
        v-model:row-selection="rowSelection"
        :columns="tableColumns"
        :data="datatable.data"
        :loading="isLoading"
        :loading-animation="loadingAnimation"
        :sticky="stickyHeader ? 'header' : undefined"
        :ui="{
          base: 'table-fixed border-separate border-spacing-0',
          thead: '[&>tr]:bg-elevated/50 [&>tr]:after:content-none',
          tbody: '[&>tr]:last:[&>td]:border-b-0',
          th: 'py-2 first:rounded-l-lg last:rounded-r-lg border-y border-default first:border-l last:border-r',
          td: 'border-b border-default',
          separator: 'h-0',
        }"
        @contextmenu="onContextMenu"
      >
        <template #empty>
          <slot name="empty">
            <div class="flex flex-col items-center justify-center gap-3 py-12">
              <UIcon class="size-12 text-muted" :name="emptyIcon" />
              <p class="text-sm text-muted">
                {{ emptyText }}
              </p>
            </div>
          </slot>
        </template>
        <template v-for="(_, slotName) in $slots" :key="slotName" #[slotName]="slotProps">
          <slot :name="slotName" v-bind="slotProps" />
        </template>
      </UTable>
    </UContextMenu>

    <!-- Footer: Pagination -->
    <div
      class="
        flex flex-col items-center gap-3
        md:flex-row md:justify-between
      "
    >
      <span class="text-sm text-muted">
        Showing {{ datatable.paginator.meta.from ?? 0 }} to {{ datatable.paginator.meta.to ?? 0 }}
        of {{ datatable.paginator.meta.total ?? 0 }} results
      </span>
      <div class="flex items-center gap-3">
        <UPagination
          :default-page="datatable.paginator.meta.current_page"
          :items-per-page="datatable.paginator.meta.per_page"
          :total="datatable.paginator.meta.total"
          @update:page="goToPage"
        />
        <UDropdownMenu :items="perPageItems">
          <UButton
            color="neutral"
            :label="String(datatable.paginator.meta.per_page)"
            variant="outline"
          >
            <template #trailing>
              <UIcon class="text-primary" name="i-heroicons-chevron-down" />
            </template>
          </UButton>
        </UDropdownMenu>
      </div>
    </div>
  </div>

  <!-- Confirmation Modal -->
  <UModal v-model:open="confirmModal">
    <template #header>
      <span class="font-semibold">Confirm Action</span>
    </template>
    <template #body>
      <p>{{ pendingAction?.message ?? 'Are you sure you want to proceed? This action cannot be undone.' }}</p>
    </template>
    <template #footer>
      <div class="flex w-full justify-end gap-2">
        <UButton
          color="neutral"
          label="Cancel"
          variant="outline"
          @click="confirmModal = false"
        />
        <UButton
          color="error"
          label="Confirm"
          @click="executeConfirmedAction"
        />
      </div>
    </template>
  </UModal>
</template>
