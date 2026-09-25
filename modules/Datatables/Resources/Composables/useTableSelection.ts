export function useTableSelection<T extends Record<string, any>>(datatable: Datatable<T>): UseTableSelectionReturn {
  const anchorKey = ref<RecordIdentifier | null>(null);

  const rowSelection = computed<Record<string, boolean>>(() =>
    Object.fromEntries(
      datatable.records
        .map((record, index) => [String(index), record.selected] as const)
        .filter(([, isSelected]) => isSelected),
    ),
  );

  const selectedCount = computed<number>(() => datatable.records.filter(record => record.selected).length);

  const deselectAll = (): void => datatable.deselectAll();

  const applySelection = (keys: readonly RecordIdentifier[], shouldSelect: boolean): void => {
    datatable.records
      .filter(record => keys.includes(record.key))
      .forEach(record => (shouldSelect ? record.select() : record.deselect()));
  };

  const handleCheckboxClick = (index: number, event: MouseEvent): void => {
    const record = datatable.records[index];

    if (!record) {
      return;
    }

    const shouldSelect = !record.selected;
    const anchor = event.shiftKey && anchorKey.value !== null ? anchorKey.value : undefined;

    applySelection(
      getBulkSelectionRange(datatable.records.map(item => item.key), anchor, record.key),
      shouldSelect,
    );

    anchorKey.value = record.key;
  };

  const handleRowSelectionChange = (newSelection: Record<string, boolean> | undefined): void => {
    if (!newSelection) {
      return;
    }

    datatable.records.forEach((record, index) => {
      const shouldBeSelected = Boolean(newSelection[String(index)]);

      if (shouldBeSelected !== record.selected) {
        if (shouldBeSelected) {
          record.select();
        } else {
          record.deselect();
        }
      }
    });
  };

  return {
    deselectAll,
    handleCheckboxClick,
    handleRowSelectionChange,
    rowSelection,
    selectedCount,
  };
}
