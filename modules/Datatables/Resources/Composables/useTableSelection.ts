type UseTableSelectionReturn<T> = {
  clearSelection: () => void;
  handleRowSelection: (rowIndex: number, isSelected: boolean, event?: MouseEvent) => void;
  rowSelection: Ref<Record<string, boolean>>;
  selectedRows: ComputedRef<T[]>;
};

export function useTableSelection<T>(
  getData: () => T[],
): UseTableSelectionReturn<T> {
  const rowSelection = ref<Record<string, boolean>>({});
  const lastSelectedIndex = ref<number | null>(null);

  const selectedRows = computed<T[]>(() => {
    const data = getData();
    return Object.keys(rowSelection.value)
      .filter(key => rowSelection.value[key])
      .map(key => data[Number.parseInt(key)]);
  });

  function handleRowSelection(rowIndex: number, isSelected: boolean, event?: MouseEvent): void {
    const newSelection = { ...rowSelection.value };

    if (event?.shiftKey && lastSelectedIndex.value !== null && lastSelectedIndex.value !== rowIndex) {
      // Shift-click: select range between last selected and current
      const start = Math.min(lastSelectedIndex.value, rowIndex);
      const end = Math.max(lastSelectedIndex.value, rowIndex);

      for (let i = start; i <= end; i++) {
        newSelection[String(i)] = true;
      }
    } else {
      // Regular click: toggle single row
      newSelection[String(rowIndex)] = isSelected;
    }

    rowSelection.value = newSelection;
    lastSelectedIndex.value = rowIndex;
  }

  function clearSelection(): void {
    rowSelection.value = {};
    lastSelectedIndex.value = null;
  }

  return {
    clearSelection,
    handleRowSelection,
    rowSelection,
    selectedRows,
  };
}
