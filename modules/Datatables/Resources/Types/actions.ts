export type TableActionMetadata = {
  readonly color?: Modules.Core.Enums.FlashMessage;
  readonly confirm?: boolean;
  readonly confirmMessage?: string;
  readonly icon?: string;
};

export type TableAction = {
  readonly execute?: () => void;
  readonly label: string;
  readonly metadata?: TableActionMetadata;
  readonly name: string;
};

/**
 * Structural type for a Hybridly table record.
 * Defined explicitly because the record shape from useTable
 * is unresolvable due to generic type parameters.
 */
export type TableRecord = {
  readonly actions: readonly TableAction[];
  readonly deselect: () => void;
  readonly execute: (name: string) => void;
  readonly select: () => void;
  readonly selected: boolean;
};

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
