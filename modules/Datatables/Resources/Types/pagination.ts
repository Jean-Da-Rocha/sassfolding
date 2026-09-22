export type TablePaginatorMeta = Paginator['meta'];

export type PaginationOptions = {
  readonly perPageOptions?: readonly number[];
};

export type PerPageItem = {
  readonly class?: string;
  readonly label: string;
  readonly onSelect: () => void;
};

export type UseTablePaginationReturn = {
  readonly changePerPage: (size: number) => void;
  readonly goToPage: (page: number) => void;
  readonly paginatorMeta: ComputedRef<TablePaginatorMeta>;
  readonly perPageItems: ComputedRef<PerPageItem[]>;
};
