export type FilterOption = {
  readonly label: string;
  readonly value: unknown;
};

export type NumericSuggestion = {
  readonly label: string;
  readonly value: number;
};

export type TimeSuggestion = {
  readonly date: string;
  readonly label: string;
};

export type UseTableFiltersReturn = {
  readonly activeFilterCount: ComputedRef<number>;
  readonly clearAllFilters: () => void;
  readonly displayFilters: ComputedRef<readonly BoundFilterRefinement[]>;
  readonly getFilterIcon: (filter: BoundFilterRefinement) => string;
  readonly getFilterItems: (filter: BoundFilterRefinement) => DropdownMenuItem[][];
  readonly getFilterLabel: (filter: BoundFilterRefinement) => string;
  readonly hasActiveFilters: ComputedRef<boolean>;
};
