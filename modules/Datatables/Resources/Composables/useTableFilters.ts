// Datatable parameter typed as `any` because ReturnType<typeof useTable>
// produces unresolvable conditional types across generic boundaries.

const FILTER_ICON_MAP = {
  boolean: 'i-lucide-toggle-left',
  date: 'i-lucide-calendar',
  select: 'i-lucide-list',
  trashed: 'i-lucide-trash-2',
} as const satisfies Record<string, string>;

const DEFAULT_FILTER_ICON = 'i-lucide-filter';

export function useTableFilters(datatable: any): UseTableFiltersReturn {
  const displayFilters = computed<readonly BoundFilterRefinement[]>(() =>
    datatable.filters.filter((filter: BoundFilterRefinement) => filter.type !== 'text' && !filter.hidden),
  );

  const hasActiveFilters = computed<boolean>(() =>
    displayFilters.value.some(filter => filter.is_active),
  );

  const activeFilterCount = computed<number>(() =>
    displayFilters.value.filter(filter => filter.is_active).length,
  );

  const getFilterLabel = (filter: BoundFilterRefinement): string => {
    if (filter.is_active && filter.metadata?.current_value_label) {
      return filter.metadata.current_value_label;
    }

    return filter.label;
  };

  const getFilterIcon = (filter: BoundFilterRefinement): string =>
    FILTER_ICON_MAP[filter.type as keyof typeof FILTER_ICON_MAP] ?? DEFAULT_FILTER_ICON;

  const getFilterItems = (filter: BoundFilterRefinement): DropdownMenuItem[][] => {
    switch (filter.type) {
      case 'boolean':
      case 'ternary':
        return buildRadioItems(datatable, filter, [
          {
            label: filter.metadata?.placeholder ?? 'All',
            value: null,
          },
          {
            label: filter.metadata?.true_label ?? 'Yes',
            value: true,
          },
          {
            label: filter.metadata?.false_label ?? 'No',
            value: false,
          },
        ]);
      case 'trashed':
        return buildRadioItems(datatable, filter, [
          {
            label: 'Without trashed',
            value: null,
          },
          {
            label: 'With trashed',
            value: 'with',
          },
          {
            label: 'Only trashed',
            value: 'only',
          },
        ]);
      case 'date':
        return buildDateItems(datatable, filter);
      case 'select':
        return buildSelectItems(datatable, filter);
      default:
        return [];
    }
  };

  const clearAllFilters = (): void => {
    if (hasActiveFilters.value) {
      datatable.clearFilters();
    }
  };

  return {
    activeFilterCount,
    clearAllFilters,
    displayFilters,
    getFilterIcon,
    getFilterItems,
    getFilterLabel,
    hasActiveFilters,
  };
}

// --- Private helper functions ---

function buildClearItem(datatable: any, filter: BoundFilterRefinement): DropdownMenuItem[] {
  if (!filter.is_active) {
    return [];
  }

  return [
    {
      color: 'error' as const,
      icon: 'i-lucide-x',
      label: 'Clear',
      onSelect: () => datatable.clearFilter(filter.name),
    },
  ];
}

function buildRadioItems(
  datatable: any,
  filter: BoundFilterRefinement,
  options: readonly FilterOption[],
): DropdownMenuItem[][] {
  return [
    options.map(option => ({
      icon: filter.value === option.value ? 'i-lucide-check' : undefined,
      label: option.label,
      onSelect: () => option.value === null
        ? datatable.clearFilter(filter.name)
        : datatable.applyFilter(filter.name, option.value),
    })),
  ];
}

function buildSelectItems(datatable: any, filter: BoundFilterRefinement): DropdownMenuItem[][] {
  const options: Record<string, string> = filter.metadata?.options ?? {};
  const isMultiple = filter.metadata?.is_multiple === true;
  const currentValues: string[] = normalizeFilterValues(filter.value);

  const isSelected = (key: string): boolean => currentValues.includes(key);

  const toggleValue = (key: string): void => {
    if (!isMultiple) {
      datatable.applyFilter(filter.name, key);
      return;
    }

    const nextValues = isSelected(key)
      ? currentValues.filter(value => value !== key)
      : [...currentValues, key];

    if (nextValues.length === 0) {
      datatable.clearFilter(filter.name);
    } else {
      datatable.applyFilter(filter.name, nextValues);
    }
  };

  const items: DropdownMenuItem[] = Object.entries(options).map(([key, label]) => ({
    icon: isSelected(key) ? 'i-lucide-check' : undefined,
    label,
    onSelect: (event: Event) => {
      if (isMultiple) {
        event.preventDefault();
      }
      toggleValue(key);
    },
  }));

  const clearItems = buildClearItem(datatable, filter);

  return clearItems.length > 0 ? [items, clearItems] : [items];
}

function buildDateItems(datatable: any, filter: BoundFilterRefinement): DropdownMenuItem[][] {
  const suggestions: readonly TimeSuggestion[] = filter.metadata?.suggestions ?? [];

  const items: DropdownMenuItem[] = suggestions.map(suggestion => ({
    icon: filter.value === suggestion.date ? 'i-lucide-check' : undefined,
    label: suggestion.label,
    onSelect: () => datatable.applyFilter(filter.name, suggestion.date),
  }));

  const clearItems = buildClearItem(datatable, filter);

  return clearItems.length > 0 ? [items, clearItems] : [items];
}

function normalizeFilterValues(value: unknown): string[] {
  if (Array.isArray(value)) {
    return value.map(String);
  }

  if (value !== null && value !== undefined) {
    return [String(value)];
  }

  return [];
}
