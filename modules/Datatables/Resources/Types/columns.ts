/**
 * Structural type for Hybridly table columns.
 * Defined explicitly because ReturnType<typeof useTable>['columns'][number]
 * is unresolvable due to generic type parameters.
 */
export type HybridlyTableColumn = {
  readonly isSortable: boolean;
  readonly isSorting: (direction?: SortDirection) => boolean;
  readonly label: string;
  readonly name: string | number | symbol;
  readonly toggleSort: (options?: { direction?: SortDirection }) => Promise<unknown>;
};

export type TableHeaderContext = {
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

export type ColumnGeneratorConfig = {
  // Typed as `any` because ReturnType<typeof useTable> produces
  // unresolvable conditional types across generic boundaries.
  readonly datatable: any;
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
