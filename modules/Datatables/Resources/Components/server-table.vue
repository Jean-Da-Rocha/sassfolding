<script setup lang="ts" generic="T extends Record<string, any>">
export type TableAction<T> = {
  color?: 'error' | 'info' | 'neutral' | 'primary' | 'secondary' | 'success' | 'warning';
  confirm?: boolean;
  confirmMessage?: string;
  icon?: string;
  label: string;
  method?: 'get' | 'delete';
  onSelect?: (record: T) => void;
  route?: string;
};

type Props = {
  table: Table<T>;
  actions?: TableAction<T>[];
  columns?: ColumnDef<T>[];
  searchable?: boolean;
  hiddenColumns?: string[];
  resourceName?: string;
};

const props = withDefaults(defineProps<Props>(), {
  hiddenColumns: () => [],
  searchable: true,
});

defineSlots<{
  create: () => void;
  empty: () => void;
  loading: () => void;
}>();

// Hybridly table integration
const datatable = useTable(props, 'table');

// Search binding with debounce
const search = datatable.bindFilter<string>('search', {
  debounce: 50,
  transformUrl: { query: { page: undefined } },
});

// Check if search filter is available
const hasSearchFilter = computed(() =>
  datatable.filters.some((f: { name: string }) => f.name === 'search'),
);

// Pagination navigation
function goToPage(page: number): void {
  router.reload({
    transformUrl: {
      query: {
        page,
        per_page: datatable.paginator.meta.per_page,
      },
    },
  });
}

// Items per page options (standard pagination sizes)
const perPageOptions = [10, 25, 50, 100];
const perPageItems = computed(() =>
  perPageOptions.map(size => ({
    class: datatable.paginator.meta.per_page === size ? 'bg-primary text-inverted' : undefined,
    label: String(size),
    onSelect: () => changePerPage(size),
  })),
);

function changePerPage(size: number): void {
  router.reload({
    transformUrl: {
      query: {
        page: 1,
        per_page: size,
      },
    },
  });
}

// Context menu for right-click actions
const contextMenuItems = ref<ReturnType<typeof getRowActions>>([]);

function onContextMenu(_event: Event, row: { original: T }): void {
  contextMenuItems.value = getRowActions(row.original);
}

// Resolve UButton for use in h() render functions
const UButton = resolveComponent('UButton');
const UDropdownMenu = resolveComponent('UDropdownMenu');

// Auto-generate columns from Hybridly
const generatedColumns = computed<ColumnDef<T>[]>(() => {
  const cols: ColumnDef<T>[] = datatable.columns.map((column) => {
    const isSortable = column.isSortable ?? false;

    // Determine sort icon based on Hybridly's column state
    const getSortIcon = () => {
      if (column.isSorting('asc')) {
        return 'i-heroicons-bars-arrow-up';
      }
      if (column.isSorting('desc')) {
        return 'i-heroicons-bars-arrow-down';
      }
      return undefined;
    };

    return {
      accessorKey: column.name,
      enableSorting: isSortable,
      header: isSortable
        ? () => {
            const sortIcon = getSortIcon();
            return h(UButton, {
              class: '-mx-2.5',
              color: 'neutral',
              label: column.label,
              // Only show icon when column is actively sorted (using Hybridly state)
              ...(sortIcon && { 'trailing-icon': sortIcon }),
              onClick: () => {
                column.toggleSort({ direction: column.isSorting('asc') ? 'desc' : 'asc' });
              },
              variant: 'ghost',
            });
          }
        : column.label,
    } as ColumnDef<T>;
  });

  // Add actions column if actions are provided
  if (props.actions && props.actions.length > 0) {
    cols.push({
      cell: ({ row }) => h(UDropdownMenu, {
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

// Confirmation modal state
const confirmModal = ref(false);
const pendingAction = ref<{
  url: string;
  method: 'get' | 'delete';
  message?: string;
} | null>(null);

function executeConfirmedAction() {
  if (!pendingAction.value) {
    return;
  }

  // TODO: dynamic router.{method} call.
  if (pendingAction.value.method === 'delete') {
    router.delete(pendingAction.value.url);
  } else {
    router.get(pendingAction.value.url);
  }

  confirmModal.value = false;
  pendingAction.value = null;
}

// Build row actions from props
function getRowActions(record: T) {
  if (!props.actions?.length) {
    return [];
  }

  return props.actions.map(action => ({
    color: action.color,
    icon: action.icon,
    label: action.label,
    onSelect: () => {
      // Custom handler takes priority
      if (action.onSelect) {
        action.onSelect(record);
        return;
      }

      // Route-based action
      if (action.route) {
        // Build route params using resourceName or 'id' as fallback
        const routeParams = props.resourceName
          ? { [props.resourceName]: record.id }
          : { id: record.id };
        const url = route(action.route, routeParams);

        // Show confirmation modal if required
        if (action.confirm) {
          pendingAction.value = {
            message: action.confirmMessage,
            method: action.method ?? 'get',
            url,
          };
          confirmModal.value = true;
          return;
        }

        // Execute directly
        if (action.method === 'delete') {
          router.delete(url);
        } else {
          router.get(url);
        }
      }
    },
  }));
}

// Use auto-generated columns if no custom columns provided
const tableColumns = computed(() => props.columns ?? generatedColumns.value);

// Column visibility state - initialize all columns as visible except hiddenColumns
const columnVisibility = ref<Record<string, boolean>>(
  Object.fromEntries([
    // All columns visible by default
    ...datatable.columns.map(col => [col.name, true]),
    // Override with hidden columns
    ...props.hiddenColumns.map(col => [col, false]),
  ]),
);

// Column visibility dropdown items - always show all columns
const visibilityItems = computed(() =>
  datatable.columns.map(col => ({
    icon: columnVisibility.value[col.name] ? 'i-heroicons-check' : undefined,
    label: col.label,
    onSelect: () => {
      columnVisibility.value = {
        ...columnVisibility.value,
        [col.name]: !columnVisibility.value[col.name],
      };
    },
  })),
);
</script>

<template>
  <UCard>
    <template #header>
      <div class="flex items-center justify-between gap-4">
        <!-- Search input -->
        <div v-if="searchable && hasSearchFilter" class="max-w-sm">
          <UInput
            v-model="search"
            icon="i-heroicons-magnifying-glass"
            placeholder="Search..."
          />
        </div>
        <div v-else /><!-- Spacer when no search -->

        <div class="flex items-center gap-2">
          <!-- Column visibility toggle -->
          <UDropdownMenu :items="visibilityItems">
            <UButton
              color="neutral"
              icon="i-heroicons-view-columns"
              variant="outline"
            />
          </UDropdownMenu>

          <!-- Create button slot -->
          <slot name="create" />
        </div>
      </div>
    </template>

    <!-- Table with context menu for right-click actions -->
    <UContextMenu :items="contextMenuItems">
      <UTable
        v-model:column-visibility="columnVisibility"
        :columns="tableColumns"
        :data="datatable.data"
        @contextmenu="onContextMenu"
      >
        <!-- Pass through slots for cell overrides -->
        <template v-for="(_, slotName) in $slots" :key="slotName" #[slotName]="slotProps">
          <slot :name="slotName" v-bind="slotProps" />
        </template>
      </UTable>
    </UContextMenu>

    <template #footer>
      <div
        class="
          flex flex-col items-center gap-3
          md:flex-row md:justify-between
        "
      >
        <!-- Shown on all sizes, centered on mobile, left on md+ -->
        <span class="text-sm text-muted">
          Showing {{ datatable.paginator.meta.from ?? 0 }} to {{ datatable.paginator.meta.to ?? 0 }}
          of {{ datatable.paginator.meta.total ?? 0 }} results
        </span>
        <div class="flex items-center gap-3">
          <!-- Pagination -->
          <UPagination
            :default-page="datatable.paginator.meta.current_page"
            :items-per-page="datatable.paginator.meta.per_page"
            :total="datatable.paginator.meta.total"
            @update:page="goToPage"
          />
          <!-- Items per page selector -->
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
    </template>
  </UCard>

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
