// Datatable parameter typed as `any` because ReturnType<typeof useTable>
// produces unresolvable conditional types across generic boundaries.
// No explicit return type: known TS2589 with deeply nested Hybridly generics.

export function useTableActions(
  datatable: any,
  requestConfirmation: (message: string | undefined, onConfirm: () => void) => void,
) {
  const bulkActions = computed<readonly TableAction[]>(() => datatable.bulkActions);
  const hasInlineActions = computed<boolean>(() => datatable.inlineActions.length > 0);
  const hasBulkActions = computed<boolean>(() => bulkActions.value.length > 0);

  const executeWithConfirmation = (action: TableAction, onExecute: () => void): void => {
    if (action.metadata?.confirm) {
      requestConfirmation(action.metadata.confirmMessage, onExecute);
      return;
    }

    onExecute();
  };

  const executeInlineAction = (action: TableAction, record: TableRecord): void => {
    executeWithConfirmation(action, () => record.execute(action.name));
  };

  const executeBulkAction = (action: TableAction): void => {
    executeWithConfirmation(action, () => action.execute?.());
  };

  const getRowActions = (rowIndex: number): DropdownMenuItem[] => {
    const record: TableRecord | undefined = datatable.records[rowIndex];

    if (!record) {
      return [];
    }

    return record.actions.map((action: TableAction) => ({
      color: action.metadata?.color,
      icon: action.metadata?.icon,
      label: action.label,
      onSelect: () => executeInlineAction(action, record),
    }));
  };

  const contextMenuItems = ref<DropdownMenuItem[]>([]);

  const onContextMenu = (_event: Event, row: { index: number }): void => {
    contextMenuItems.value = getRowActions(row.index);
  };

  return {
    bulkActions,
    contextMenuItems,
    executeBulkAction,
    getRowActions,
    hasBulkActions,
    hasInlineActions,
    onContextMenu,
  };
}
