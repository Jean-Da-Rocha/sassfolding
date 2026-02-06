/**
 * Structural type for Hybridly table columns.
 * Defined explicitly because ReturnType<typeof useTable>['columns'][number]
 * is unresolvable due to unresolved generic type parameters.
 */
export type HybridlyTableColumn = {
  readonly name: string | number | symbol;
  readonly label: string;
  readonly isSortable: boolean;
  readonly isSorting: (direction?: string) => boolean;
  readonly toggleSort: (options?: { direction?: string }) => Promise<unknown>;
};

export type TableRowContext<T> = {
  readonly row: {
    readonly original: T;
    readonly index: number;
    readonly getIsSelected: () => boolean;
  };
};

export type TableHeaderContext = {
  readonly table: {
    readonly getIsAllPageRowsSelected: () => boolean;
    readonly getIsSomePageRowsSelected: () => boolean;
    readonly toggleAllPageRowsSelected: (value: boolean) => void;
  };
};

export type ColumnGeneratorConfig<T extends Record<string, any>> = {
  readonly datatable: ReturnType<typeof useTable>;
  readonly getRowActions: (record: T) => readonly RowActionItem[];
  readonly handleRowSelection: (rowIndex: number, isSelected: boolean, event?: MouseEvent) => void;
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
  readonly onSelect: () => void;
};

export type UseTableColumnVisibilityReturn = {
  readonly columnVisibility: Ref<Record<string, boolean>>;
  readonly visibilityItems: ComputedRef<VisibilityItem[]>;
};
