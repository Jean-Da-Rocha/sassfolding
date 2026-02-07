type HttpMethod = 'get' | 'delete';

type BaseAction = {
  readonly color?: Modules.Core.Enums.FlashMessage;
  readonly icon?: string;
  readonly label: string;
};

type RouteBasedAction = BaseAction & {
  readonly confirm?: boolean;
  readonly confirmMessage?: string;
  readonly method?: HttpMethod;
  readonly onSelect?: never;
  readonly route: RouteName;
};

type CustomHandlerAction<T extends Record<string, any>> = BaseAction & {
  readonly confirm?: never;
  readonly confirmMessage?: never;
  readonly method?: never;
  readonly onSelect: (record: T) => void;
  readonly route?: never;
};

export type TableAction<T extends Record<string, any>>
  = | RouteBasedAction
    | CustomHandlerAction<T>;

export type InlineAction<T extends Record<string, any>> = TableAction<T>;

export type BulkAction<T extends Record<string, any>> = BaseAction & {
  readonly onSelect: (selectedRows: readonly T[]) => void;
};

export type PendingAction = {
  readonly message?: string;
  readonly method: HttpMethod;
  readonly url: string;
};

export type RowActionItem = {
  readonly color?: string;
  readonly icon?: string;
  readonly label: string;
  readonly onSelect: () => void;
};

export type UseTableActionsReturn<T extends Record<string, any>> = {
  readonly confirmModal: Ref<boolean>;
  readonly contextMenuItems: Ref<RowActionItem[]>;
  readonly executeConfirmedAction: () => void;
  readonly getRowActions: (record: T) => RowActionItem[];
  readonly onContextMenu: (event: Event, row: { original: T }) => void;
  readonly pendingAction: Ref<PendingAction | null>;
};
