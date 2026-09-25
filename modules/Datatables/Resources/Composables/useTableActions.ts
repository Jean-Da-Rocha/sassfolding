export function useTableActions<T extends Record<string, any>>(
  datatable: Datatable<T>,
  requestConfirmation: (message: string | undefined, onConfirm: () => void) => void,
) {
  const bulkActions = computed<readonly TableBulkAction[]>(() => datatable.bulkActions);
  const hasInlineActions = computed<boolean>(() => datatable.inlineActions.length > 0);
  const hasBulkActions = computed<boolean>(() => bulkActions.value.length > 0);

  const executeWithConfirmation = (action: TableAction<T>, onExecute: () => void): void => {
    if (action.metadata?.confirm) {
      requestConfirmation(action.metadata.confirm_message ?? undefined, onExecute);
      return;
    }

    onExecute();
  };

  const executeRecordAction = (action: TableRecordAction<T>): void => {
    executeWithConfirmation(action, () => action.execute());
  };

  const executeBulkAction = (action: TableBulkAction): void => {
    executeWithConfirmation(action, () => action.execute());
  };

  const getRowActions = (rowIndex: number): DropdownMenuItem[] => {
    const record = datatable.records[rowIndex];

    if (!record) {
      return [];
    }

    return record.actions.map((action: TableRecordAction<T>) => ({
      color: action.metadata?.color ?? undefined,
      icon: action.metadata?.icon ?? undefined,
      label: action.label,
      onSelect: () => executeRecordAction(action),
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
