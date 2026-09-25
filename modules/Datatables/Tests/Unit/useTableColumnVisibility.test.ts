import { describe, expect, it, vi } from 'vitest';

function createDatatable(names: readonly string[]) {
  return {
    columns: names.map(name => ({
      label: name.toUpperCase(),
      name,
    })),
  } as unknown as Datatable<Record<string, any>>;
}

const selectEvent = (): Event => ({ preventDefault: vi.fn() } as unknown as Event);

describe('useTableColumnVisibility', () => {
  it('shows every column when none is hidden', () => {
    const { columnVisibility } = useTableColumnVisibility(createDatatable(['id', 'name']), []);

    expect(columnVisibility.value).toEqual({
      id: true,
      name: true,
    });
  });

  it('hides the columns given as hidden', () => {
    const { columnVisibility } = useTableColumnVisibility(createDatatable(['id', 'name', 'email']), ['email']);

    expect(columnVisibility.value).toEqual({
      email: false,
      id: true,
      name: true,
    });
  });

  it('builds one dropdown entry per column, checked when visible', () => {
    const { visibilityItems } = useTableColumnVisibility(createDatatable(['id', 'email']), ['email']);

    expect(visibilityItems.value.map(item => [item.label, item.icon])).toEqual([
      ['ID', 'i-lucide-check'],
      ['EMAIL', undefined],
    ]);
  });

  it('toggles a column and prevents the dropdown from closing', () => {
    const { columnVisibility, visibilityItems } = useTableColumnVisibility(createDatatable(['id']), []);
    const event = selectEvent();

    visibilityItems.value[0]!.onSelect(event);

    expect(columnVisibility.value.id).toBe(false);
    expect(event.preventDefault).toHaveBeenCalledOnce();
  });
});
