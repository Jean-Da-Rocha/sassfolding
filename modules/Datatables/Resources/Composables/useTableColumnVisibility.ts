// Datatable parameter typed as `any` because ReturnType<typeof useTable>
// produces unresolvable conditional types across generic boundaries.

export function useTableColumnVisibility(datatable: any, hiddenColumns: readonly string[]): UseTableColumnVisibilityReturn {
  const columnVisibility = ref<Record<string, boolean>>(
    buildInitialVisibility(datatable.columns, hiddenColumns),
  );

  const visibilityItems = computed<VisibilityItem[]>(() =>
    datatable.columns.map((column: HybridlyTableColumn) => {
      const columnName = String(column.name);

      return {
        icon: columnVisibility.value[columnName] ? 'i-lucide-check' : undefined,
        label: column.label,
        onSelect: (event: Event) => {
          event.preventDefault();
          toggleColumnVisibility(columnName);
        },
      };
    }),
  );

  function toggleColumnVisibility(columnName: string): void {
    columnVisibility.value = {
      ...columnVisibility.value,
      [columnName]: !columnVisibility.value[columnName],
    };
  }

  return {
    columnVisibility,
    visibilityItems,
  };
}

function buildInitialVisibility(
  columns: readonly HybridlyTableColumn[],
  hiddenColumns: readonly string[],
): Record<string, boolean> {
  const hiddenSet = new Set(hiddenColumns);

  return Object.fromEntries(
    columns.map((column) => {
      const columnName = String(column.name);

      return [columnName, !hiddenSet.has(columnName)];
    }),
  );
}
