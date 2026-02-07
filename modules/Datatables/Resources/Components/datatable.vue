<script setup lang="ts" generic="T extends Record<string, any>">
type Props = {
  table: Table<T>;
  bulkActions?: readonly BulkAction<T>[];
  columns?: ColumnDef<T>[];
  emptyIcon?: string;
  emptyText?: string;
  hiddenColumns?: readonly string[];
  inlineActions?: readonly TableAction<T>[];
  loadingAnimation?: 'carousel' | 'carousel-inverse' | 'elastic' | 'swing';
  resourceName?: string;
  searchable?: boolean;
  selectable?: boolean;
  stickyHeader?: boolean;
};

const props = withDefaults(defineProps<Props>(), {
  emptyIcon: 'i-lucide-database',
  emptyText: 'No data available',
  hiddenColumns: () => [],
  loadingAnimation: 'carousel',
  searchable: true,
  selectable: false,
  stickyHeader: false,
});

defineSlots<{
  [key: string]: (props?: {
    row?: {
      original: T;
      index: number;
    };
    column?: ColumnDef<T>;
  }) => unknown;
}>();

const datatable = useTable(props, 'table');
const isLoading = useHybridlyLoading();

const { hasSearchFilter, search } = useTableSearch(datatable);
const { goToPage, paginatorMeta, perPageItems } = useTablePagination(datatable);
const { clearSelection, handleRowSelection, rowSelection, selectedRows } = useTableSelection<T>(() => datatable.data);
const { columnVisibility, visibilityItems } = useTableColumnVisibility(datatable, props.hiddenColumns as readonly string[]);
const {
  confirmModal,
  contextMenuItems,
  executeConfirmedAction,
  getRowActions,
  onContextMenu,
  pendingAction,
} = useTableActions<T>(props.inlineActions, props.resourceName);

// resolveComponent must be called in setup context
const { columns: generatedColumns } = useTableColumns<T>(
  {
    datatable,
    getRowActions,
    handleRowSelection,
    hasInlineActions: Boolean(props.inlineActions?.length),
    selectable: props.selectable,
  },
  {
    UButton: resolveComponent('UButton') as Component,
    UCheckbox: resolveComponent('UCheckbox') as Component,
    UDropdownMenu: resolveComponent('UDropdownMenu') as Component,
  },
);

const tableColumns = computed(() => props.columns ?? generatedColumns.value);
</script>

<template>
  <div class="flex flex-col gap-4">
    <!-- Header: Search + Actions -->
    <div class="flex items-center justify-between gap-4">
      <div v-if="searchable && hasSearchFilter" class="max-w-sm">
        <UInput
          v-model="search"
          icon="i-lucide-search"
          placeholder="Search..."
        />
      </div>
      <div v-else />

      <div class="flex items-center gap-2">
        <UDropdownMenu :items="visibilityItems">
          <UButton
            color="neutral"
            icon="i-lucide-columns-3"
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
        icon="i-lucide-x"
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
        Showing {{ paginatorMeta.from ?? 0 }} to {{ paginatorMeta.to ?? 0 }}
        of {{ paginatorMeta.total ?? 0 }} results
      </span>
      <div class="flex items-center gap-3">
        <UPagination
          :default-page="paginatorMeta.current_page"
          :items-per-page="paginatorMeta.per_page"
          :total="paginatorMeta.total"
          @update:page="goToPage"
        />
        <UDropdownMenu :items="perPageItems">
          <UButton
            color="neutral"
            :label="String(paginatorMeta.per_page)"
            variant="outline"
          >
            <template #trailing>
              <UIcon class="text-primary" name="i-lucide-chevron-down" />
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
