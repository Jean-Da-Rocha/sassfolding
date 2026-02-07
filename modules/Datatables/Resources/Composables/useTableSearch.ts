export function useTableSearch(datatable: ReturnType<typeof useTable>, options?: SearchOptions): UseTableSearchReturn {
  const search = datatable.bindFilter('search', {
    debounce: options?.debounce ?? 300,
    syncDebounce: 0,
    transformUrl: { query: { page: undefined } },
  }) as Ref<string>;

  const hasSearchFilter = computed(() =>
    datatable.filters.some((filter: { name: string }) => filter.name === 'search'),
  );

  return {
    hasSearchFilter,
    search,
  };
}
