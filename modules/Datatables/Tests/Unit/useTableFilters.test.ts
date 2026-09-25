import { describe, expect, it, vi } from 'vitest';

type FilterOverrides = {
  readonly hidden?: boolean;
  readonly is_active?: boolean;
  readonly label?: string;
  readonly metadata?: Record<string, unknown>;
  readonly type?: string;
  readonly value?: unknown;
};

function filter(name: string, overrides: FilterOverrides = {}) {
  return {
    hidden: overrides.hidden ?? false,
    is_active: overrides.is_active ?? false,
    label: overrides.label ?? name,
    metadata: overrides.metadata ?? {},
    name,
    type: overrides.type ?? 'select',
    value: overrides.value ?? null,
  } as unknown as BoundFilterRefinement;
}

function createDatatable(filters: readonly unknown[], hooks: Record<string, unknown> = {}) {
  return {
    applyFilter: vi.fn(),
    clearFilters: vi.fn(),
    filters,
    ...hooks,
  } as unknown as Datatable<Record<string, any>>;
}

describe('useTableFilters', () => {
  it('hides text filters, which are driven by the search box', () => {
    const { displayFilters } = useTableFilters(createDatatable([
      filter('search', { type: 'text' }),
      filter('status'),
    ]));

    expect(displayFilters.value.map(item => item.name)).toEqual(['status']);
  });

  it('hides filters flagged as hidden', () => {
    const { displayFilters } = useTableFilters(createDatatable([filter('status', { hidden: true })]));

    expect(displayFilters.value).toHaveLength(0);
  });

  it('counts only the active filters it displays', () => {
    const { activeFilterCount, hasActiveFilters } = useTableFilters(createDatatable([
      filter('search', {
        is_active: true,
        type: 'text',
      }),
      filter('status', { is_active: true }),
      filter('priority'),
    ]));

    expect(activeFilterCount.value).toBe(1);
    expect(hasActiveFilters.value).toBe(true);
  });

  it('shows the selected value as label once a filter is active', () => {
    const { getFilterLabel } = useTableFilters(createDatatable([]));

    expect(getFilterLabel(filter('status', {
      is_active: true,
      label: 'Status',
      metadata: { current_value_label: 'Done' },
    }))).toBe('Done');
  });

  it('falls back to the filter label when it carries no selected value', () => {
    const { getFilterLabel } = useTableFilters(createDatatable([]));

    expect(getFilterLabel(filter('status', {
      is_active: true,
      label: 'Status',
    }))).toBe('Status');
  });

  it('gives each filter type its own icon', () => {
    const { getFilterIcon } = useTableFilters(createDatatable([]));

    expect(getFilterIcon(filter('a', { type: 'date' }))).toBe('i-lucide-calendar');
    expect(getFilterIcon(filter('b', { type: 'boolean' }))).toBe('i-lucide-toggle-left');
    expect(getFilterIcon(filter('c', { type: 'numeric' }))).toBe('i-lucide-filter');
  });

  it('marks the date suggestion that Hybridly reports as current', () => {
    const { getFilterItems } = useTableFilters(createDatatable([]));

    const [suggestions] = getFilterItems(filter('due_at', {
      metadata: {
        suggestions: [
          {
            date: '2026-09-22',
            is_current: false,
            label: 'Today',
          },
          {
            date: '2026-09-29',
            is_current: true,
            label: 'Next week',
          },
        ],
      },
      type: 'date',
    }));

    expect(suggestions!.map(item => [item.label, item.icon])).toEqual([
      ['Today', undefined],
      ['Next week', 'i-lucide-check'],
    ]);
  });

  it('builds no entry for a filter type it does not handle', () => {
    const { getFilterItems } = useTableFilters(createDatatable([]));

    expect(getFilterItems(filter('amount', { type: 'numeric' }))).toEqual([]);
  });

  it('clears the filters only when at least one is active', () => {
    const datatable = createDatatable([filter('status')]);
    const { clearAllFilters } = useTableFilters(datatable);

    clearAllFilters();

    expect(datatable.clearFilters).not.toHaveBeenCalled();
  });

  it('clears the filters when one is active', () => {
    const datatable = createDatatable([filter('status', { is_active: true })]);
    const { clearAllFilters } = useTableFilters(datatable);

    clearAllFilters();

    expect(datatable.clearFilters).toHaveBeenCalledOnce();
  });
});
