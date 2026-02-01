type UseTableSearchReturn = {
  hasSearchFilter: ComputedRef<boolean>;
  search: Ref<string>;
};

export function useTableSearch(
  datatable: ReturnType<typeof useTable>,
  options?: { debounce?: number },
): UseTableSearchReturn {
  const search = datatable.bindFilter('search', {
    debounce: options?.debounce ?? 50,
    transformUrl: { query: { page: undefined } },
  }) as Ref<string>;

  const hasSearchFilter = computed(() =>
    datatable.filters.some((f: { name: string }) => f.name === 'search'),
  );

  return {
    hasSearchFilter,
    search,
  };
}
