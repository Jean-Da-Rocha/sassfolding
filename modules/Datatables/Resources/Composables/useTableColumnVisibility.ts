export function useTableColumnVisibility(datatable: ReturnType<typeof useTable>, hiddenColumns: readonly string[]): UseTableColumnVisibilityReturn {
  const columnVisibility = ref<Record<string, boolean>>(
    buildInitialVisibility(datatable.columns, hiddenColumns),
  );

  const visibilityItems = computed<VisibilityItem[]>(() =>
    datatable.columns.map((column: HybridlyTableColumn) => ({
      icon: columnVisibility.value[String(column.name)] ? 'i-lucide-check' : undefined,
      label: column.label,
      onSelect: (event: Event) => {
        event.preventDefault();

        toggleColumnVisibility(String(column.name));
      },
    })),
  );

  function buildInitialVisibility(
    columns: readonly { name: string | number }[],
    hidden: readonly string[],
  ): Record<string, boolean> {
    return Object.fromEntries([
      ...columns.map(column => [String(column.name), true]),
      ...hidden.map(columnName => [columnName, false]),
    ]);
  }

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
