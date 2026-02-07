export type SearchOptions = {
  readonly debounce?: number;
};

export type UseTableSearchReturn = {
  readonly hasSearchFilter: ComputedRef<boolean>;
  readonly search: Ref<string>;
};
