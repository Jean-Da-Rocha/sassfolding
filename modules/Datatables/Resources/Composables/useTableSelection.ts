// Datatable parameter typed as `any` because ReturnType<typeof useTable>
// produces unresolvable conditional types across generic boundaries.

export function useTableSelection(datatable: any): UseTableSelectionReturn {
  const lastClickedIndex = ref<number | null>(null);

  const rowSelection = computed<Record<string, boolean>>(() =>
    Object.fromEntries(
      (datatable.records as TableRecord[])
        .map((record, index) => [String(index), record.selected] as const)
        .filter(([, isSelected]) => isSelected),
    ),
  );

  const selectedCount = computed<number>(() =>
    (datatable.records as TableRecord[]).filter(record => record.selected).length,
  );

  const deselectAll = (): void => datatable.deselectAll();

  const setRecordSelection = (index: number, shouldSelect: boolean): void => {
    const record: TableRecord | undefined = datatable.records[index];

    if (!record || record.selected === shouldSelect) {
      return;
    }

    if (shouldSelect) {
      record.select();
    } else {
      record.deselect();
    }
  };

  const handleCheckboxClick = (index: number, event: MouseEvent): void => {
    const record: TableRecord | undefined = datatable.records[index];
    if (!record) {
      return;
    }

    const willSelect = !record.selected;

    if (event.shiftKey && lastClickedIndex.value !== null) {
      const rangeStart = Math.min(lastClickedIndex.value, index);
      const rangeEnd = Math.max(lastClickedIndex.value, index);

      for (let i = rangeStart; i <= rangeEnd; i++) {
        setRecordSelection(i, willSelect);
      }
    } else {
      setRecordSelection(index, willSelect);
    }

    lastClickedIndex.value = index;
  };

  const handleRowSelectionChange = (newSelection: Record<string, boolean> | undefined): void => {
    if (!newSelection) {
      return;
    }

    (datatable.records as TableRecord[]).forEach((record, index) => {
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
