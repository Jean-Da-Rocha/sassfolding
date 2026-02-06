export function useTableActions<T extends Record<string, any>>(inlineActions: readonly TableAction<T>[] | undefined, resourceName?: string): UseTableActionsReturn<T> {
  const confirmModal = ref(false);
  const pendingAction = ref<PendingAction | null>(null);
  const contextMenuItems = ref<RowActionItem[]>([]);

  const executeConfirmedAction = (): void => {
    if (!pendingAction.value) {
      return;
    }

    if (pendingAction.value.method === 'delete') {
      void router.delete(pendingAction.value.url);
    } else {
      void router.navigate({ url: pendingAction.value.url });
    }

    confirmModal.value = false;
    pendingAction.value = null;
  };

  const getRowActions = (record: T): RowActionItem[] => {
    if (!inlineActions?.length) {
      return [];
    }

    return inlineActions.map(action => ({
      color: action.color,
      icon: action.icon,
      label: action.label,
      onSelect: () => {
        if (action.onSelect) {
          action.onSelect(record);

          return;
        }

        if (action.route) {
          const routeParams = resourceName ? { [resourceName]: record.id } : { id: record.id };
          const url = route(action.route, routeParams);

          if (action.confirm) {
            pendingAction.value = {
              message: action.confirmMessage,
              method: action.method ?? 'get',
              url,
            };

            confirmModal.value = true;

            return;
          }

          if (action.method === 'delete') {
            void router.delete(url);
          } else {
            void router.navigate({ url });
          }
        }
      },
    }));
  };

  const onContextMenu = (_event: Event, row: { original: T }): void => {
    contextMenuItems.value = getRowActions(row.original);
  };

  return {
    confirmModal,
    contextMenuItems,
    executeConfirmedAction,
    getRowActions,
    onContextMenu,
    pendingAction,
  };
}
