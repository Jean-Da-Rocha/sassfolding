export type TableActionColor = 'error' | 'info' | 'neutral' | 'primary' | 'secondary' | 'success' | 'warning';

export type TableAction<T> = {
  color?: TableActionColor;
  confirm?: boolean;
  confirmMessage?: string;
  icon?: string;
  label: string;
  method?: 'get' | 'delete';
  onSelect?: (record: T) => void;
  route?: RouteName;
};

export type InlineAction<T> = TableAction<T>;

export type BulkAction<T> = {
  color?: TableActionColor;
  icon?: string;
  label: string;
  onSelect: (selectedRows: T[]) => void;
};

export type PendingAction = {
  message?: string;
  method: 'get' | 'delete';
  url: string;
};
