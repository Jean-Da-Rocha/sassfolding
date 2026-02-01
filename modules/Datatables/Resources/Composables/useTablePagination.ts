type PerPageItem = {
  class?: string;
  label: string;
  onSelect: () => void;
};

type UseTablePaginationReturn = {
  changePerPage: (size: number) => void;
  goToPage: (page: number) => void;
  perPageItems: ComputedRef<PerPageItem[]>;
};

export function useTablePagination(
  datatable: ReturnType<typeof useTable>,
  options?: { perPageOptions?: number[] },
): UseTablePaginationReturn {
  const perPageOptions = options?.perPageOptions ?? [10, 25, 50, 100];

  function goToPage(page: number): void {
    router.reload({
      transformUrl: {
        query: {
          page,
          per_page: datatable.paginator.meta.per_page,
        },
      },
    });
  }

  function changePerPage(size: number): void {
    router.reload({
      transformUrl: {
        query: {
          page: 1,
          per_page: size,
        },
      },
    });
  }

  const perPageItems = computed<PerPageItem[]>(() =>
    perPageOptions.map(size => ({
      class: datatable.paginator.meta.per_page === size ? 'bg-primary text-inverted' : undefined,
      label: String(size),
      onSelect: () => changePerPage(size),
    })),
  );

  return {
    changePerPage,
    goToPage,
    perPageItems,
  };
}
