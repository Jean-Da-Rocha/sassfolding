export function useTablePagination(datatable: ReturnType<typeof useTable>, options?: PaginationOptions): UseTablePaginationReturn {
  const perPageChoices = options?.perPageOptions ?? [10, 25, 50, 100];

  const paginatorMeta = computed(
    () => datatable.paginator.meta as TablePaginatorMeta,
  );

  const goToPage = (page: number): void => {
    void router.reload({
      transformUrl: {
        query: {
          page,
          per_page: paginatorMeta.value.per_page,
        },
      },
    });
  };

  const changePerPage = (size: number): void => {
    void router.reload({
      transformUrl: {
        query: {
          page: 1,
          per_page: size,
        },
      },
    });
  };

  const perPageItems = computed<PerPageItem[]>(() =>
    perPageChoices.map((size: number) => ({
      class: paginatorMeta.value.per_page === size ? 'bg-primary text-inverted' : undefined,
      label: String(size),
      onSelect: () => changePerPage(size),
    })),
  );

  return {
    changePerPage,
    goToPage,
    paginatorMeta,
    perPageItems,
  };
}
