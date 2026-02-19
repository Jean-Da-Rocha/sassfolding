/**
 * Paginator metadata for length-aware pagination.
 * Defined explicitly because Hybridly's PaginatorMeta is unresolvable
 * through ReturnType<typeof useTable> due to nested conditional types.
 */
export type TablePaginatorMeta = {
  readonly current_page: number;
  readonly first_page: number;
  readonly first_page_url: string;
  readonly from: number;
  readonly last_page: number;
  readonly last_page_url: string;
  readonly next_page_url?: string;
  readonly path: string;
  readonly per_page: number;
  readonly prev_page_url?: string;
  readonly to: number;
  readonly total: number;
};

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
