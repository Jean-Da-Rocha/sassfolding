export type ColumnPinningPosition = false | 'left' | 'right';

export type TableHeaderContext = {
  readonly column: {
    readonly getIsPinned: () => ColumnPinningPosition;
    readonly pin: (position: ColumnPinningPosition) => void;
  };
  readonly table: {
    readonly getIsAllPageRowsSelected: () => boolean;
    readonly getIsSomePageRowsSelected: () => boolean;
    readonly toggleAllPageRowsSelected: (value: boolean) => void;
  };
};

export type TableRowContext<T> = {
  readonly row: {
    readonly getIsSelected: () => boolean;
    readonly index: number;
    readonly original: T;
    readonly toggleSelected: (value: boolean) => void;
  };
};

export type ColumnGeneratorConfig<T extends Record<string, any>> = {
  readonly datatable: Datatable<T>;
  readonly getRowActions: (rowIndex: number) => readonly DropdownMenuItem[];
  readonly handleCheckboxClick: (index: number, event: MouseEvent) => void;
  readonly hasInlineActions: boolean;
  readonly selectable: boolean;
};

export type ResolvedComponents = {
  readonly UButton: Component;
  readonly UCheckbox: Component;
  readonly UDropdownMenu: Component;
};

export type UseTableColumnsReturn<T extends Record<string, any>> = {
  readonly columns: ComputedRef<ColumnDef<T>[]>;
};

export type VisibilityItem = {
  readonly icon?: string;
  readonly label: string;
  readonly onSelect: (event: Event) => void;
};

export type UseTableColumnVisibilityReturn = {
  readonly columnVisibility: Ref<Record<string, boolean>>;
  readonly visibilityItems: ComputedRef<VisibilityItem[]>;
};
