const DEFAULT_DEBOUNCE_MS = 300;

export function useTableSearch<T extends Record<string, any>>(
  datatable: Datatable<T>,
  options?: SearchOptions,
): UseTableSearchReturn {
  const search: Ref<string> = datatable.bindFilter('search', {
    debounce: options?.debounce ?? DEFAULT_DEBOUNCE_MS,
    syncDebounce: 0,
    transformUrl: { query: { page: undefined } },
  });

  const hasSearchFilter = computed<boolean>(() =>
    datatable.filters.some((filter: BoundFilterRefinement) => filter.name === 'search'),
  );

  return {
    hasSearchFilter,
    search,
  };
}
