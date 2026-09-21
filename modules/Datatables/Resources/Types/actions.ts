export type TableActionMetadata = Modules.Datatables.Data.ActionMetaData;

export type TableBulkAction = UseTableBulkActionItem & {
  readonly metadata?: TableActionMetadata;
};

export type TableInlineAction<T extends Record<string, any> = Record<string, any>> = UseTableInlineActionItem<T> & {
  readonly metadata?: TableActionMetadata;
};

export type TableRecord<T extends Record<string, any> = Record<string, any>> = UseTableRecordItem<T>;

/** An inline action already bound to one record, as exposed by `record.actions`. */
export type TableRecordAction<T extends Record<string, any> = Record<string, any>> = TableRecord<T>['actions'][number] & {
  readonly metadata?: TableActionMetadata;
};

export type TableAction<T extends Record<string, any> = Record<string, any>>
  = | TableBulkAction
    | TableInlineAction<T>
    | TableRecordAction<T>;

export type PendingConfirmation = {
  readonly message?: string;
  readonly onConfirm: () => void;
};

export type UseTableConfirmationReturn = {
  readonly confirmModal: Ref<boolean>;
  readonly executeConfirmedAction: () => void;
  readonly pendingAction: Ref<PendingConfirmation | null>;
  readonly requestConfirmation: (message: string | undefined, onConfirm: () => void) => void;
};

export type UseTableSelectionReturn = {
  readonly deselectAll: () => void;
  readonly handleCheckboxClick: (index: number, event: MouseEvent) => void;
  readonly handleRowSelectionChange: (newSelection: Record<string, boolean> | undefined) => void;
  readonly rowSelection: ComputedRef<Record<string, boolean>>;
  readonly selectedCount: ComputedRef<number>;
};
