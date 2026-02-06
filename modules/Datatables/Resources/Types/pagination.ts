/**
 * Paginator metadata for length-aware pagination.
 * Mirrors Hybridly's PaginatorMeta which is unresolvable through
 * ReturnType<typeof useTable> due to nested conditional types.
 */
export type TablePaginatorMeta = {
  readonly path: string;
  readonly from: number;
  readonly to: number;
  readonly total: number;
  readonly per_page: number;
  readonly current_page: number;
  readonly first_page: number;
  readonly last_page: number;
  readonly first_page_url: string;
  readonly last_page_url: string;
  readonly next_page_url?: string;
  readonly prev_page_url?: string;
};

export type PerPageItem = {
  readonly class?: string;
  readonly label: string;
  readonly onSelect: () => void;
};

export type PaginationOptions = {
  readonly perPageOptions?: readonly number[];
};

export type UseTablePaginationReturn = {
  readonly changePerPage: (size: number) => void;
  readonly goToPage: (page: number) => void;
  readonly paginatorMeta: ComputedRef<TablePaginatorMeta>;
  readonly perPageItems: ComputedRef<PerPageItem[]>;
};
