// Datatable parameter typed as `any` because ReturnType<typeof useTable>
// produces unresolvable conditional types across generic boundaries.

const DEFAULT_PER_PAGE_OPTIONS = [10, 25, 50, 100] as const;

export function useTablePagination(datatable: any, options?: PaginationOptions): UseTablePaginationReturn {
  const perPageChoices = options?.perPageOptions ?? DEFAULT_PER_PAGE_OPTIONS;

  const paginatorMeta = computed<TablePaginatorMeta>(
    () => datatable.paginator.meta,
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
    perPageChoices.map(size => ({
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
