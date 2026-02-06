export function useTableSelection<T extends Record<string, any>>(getData: () => T[]): UseTableSelectionReturn<T> {
  const rowSelection = ref<Record<string, boolean>>({});
  const lastSelectedIndex = ref<number | null>(null);

  const selectedRows = computed<readonly T[]>(() => {
    const data = getData();
    return Object.keys(rowSelection.value)
      .filter(key => rowSelection.value[key])
      .map(key => data[Number.parseInt(key)]);
  });

  const handleRowSelection = (rowIndex: number, isSelected: boolean, event?: MouseEvent): void => {
    const newSelection = { ...rowSelection.value };

    if (event?.shiftKey && lastSelectedIndex.value !== null && lastSelectedIndex.value !== rowIndex) {
      const start = Math.min(lastSelectedIndex.value, rowIndex);
      const end = Math.max(lastSelectedIndex.value, rowIndex);

      for (let i = start; i <= end; i++) {
        newSelection[String(i)] = true;
      }
    } else {
      newSelection[String(rowIndex)] = isSelected;
    }

    rowSelection.value = newSelection;
    lastSelectedIndex.value = rowIndex;
  };

  const clearSelection = (): void => {
    rowSelection.value = {};
    lastSelectedIndex.value = null;
  };

  return {
    clearSelection,
    handleRowSelection,
    rowSelection,
    selectedRows,
  };
}
