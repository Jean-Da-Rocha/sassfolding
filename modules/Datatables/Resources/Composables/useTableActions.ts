import type { PendingAction, TableAction } from '../Types/table';

type RowActionItem = {
  color?: string;
  icon?: string;
  label: string;
  onSelect: () => void;
};

type UseTableActionsReturn<T> = {
  confirmModal: Ref<boolean>;
  contextMenuItems: Ref<RowActionItem[]>;
  executeConfirmedAction: () => void;
  getRowActions: (record: T) => RowActionItem[];
  onContextMenu: (event: Event, row: { original: T }) => void;
  pendingAction: Ref<PendingAction | null>;
};

export function useTableActions<T extends Record<string, any>>(
  inlineActions: TableAction<T>[] | undefined,
  resourceName?: string,
): UseTableActionsReturn<T> {
  const confirmModal = ref(false);
  const pendingAction = ref<PendingAction | null>(null);
  const contextMenuItems = ref<RowActionItem[]>([]);

  function executeConfirmedAction(): void {
    if (!pendingAction.value) {
      return;
    }

    if (pendingAction.value.method === 'delete') {
      router.delete(pendingAction.value.url);
    } else {
      router.navigate({ url: pendingAction.value.url });
    }

    confirmModal.value = false;
    pendingAction.value = null;
  }

  function getRowActions(record: T): RowActionItem[] {
    if (!inlineActions?.length) {
      return [];
    }

    return inlineActions.map(action => ({
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
          // Build route params dynamically using resourceName or 'id' as fallback
          const routeParams = resourceName
            ? { [resourceName]: record.id }
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
            router.navigate({ url });
          }
        }
      },
    }));
  }

  function onContextMenu(_event: Event, row: { original: T }): void {
    contextMenuItems.value = getRowActions(row.original);
  }

  return {
    confirmModal,
    contextMenuItems,
    executeConfirmedAction,
    getRowActions,
    onContextMenu,
    pendingAction,
  };
}
