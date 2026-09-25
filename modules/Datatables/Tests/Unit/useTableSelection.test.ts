import { describe, expect, it } from 'vitest';

type FakeRecord = {
  key: number;
  selected: boolean;
  select: () => void;
  deselect: () => void;
};

function createDatatable(count: number, selectedKeys: readonly number[] = []) {
  const records: FakeRecord[] = reactive(
    Array.from({ length: count }, (_, index) => ({
      deselect(): void {
        this.selected = false;
      },
      key: index + 1,
      select(): void {
        this.selected = true;
      },
      selected: selectedKeys.includes(index + 1),
    })),
  );

  return {
    datatable: {
      deselectAll: () => records.forEach(record => (record.selected = false)),
      records,
    } as unknown as Datatable<Record<string, any>>,
    records,
  };
}

function selectedKeysOf(records: readonly FakeRecord[]): number[] {
  return records.filter(record => record.selected).map(record => record.key);
}

const click = (shiftKey = false): MouseEvent => ({ shiftKey } as MouseEvent);

describe('useTableSelection', () => {
  it('selects a single record on a plain click', () => {
    const { datatable, records } = createDatatable(5);
    const { handleCheckboxClick, selectedCount } = useTableSelection(datatable);

    handleCheckboxClick(2, click());

    expect(selectedKeysOf(records)).toEqual([3]);
    expect(selectedCount.value).toBe(1);
  });

  it('selects the whole range between the anchor and the target on a shift click', () => {
    const { datatable, records } = createDatatable(6);
    const { handleCheckboxClick } = useTableSelection(datatable);

    handleCheckboxClick(1, click());
    handleCheckboxClick(4, click(true));

    expect(selectedKeysOf(records)).toEqual([2, 3, 4, 5]);
  });

  it('deselects the whole range when the target was already selected', () => {
    const { datatable, records } = createDatatable(5, [1, 2, 3, 4, 5]);
    const { handleCheckboxClick } = useTableSelection(datatable);

    handleCheckboxClick(0, click());
    handleCheckboxClick(3, click(true));

    expect(selectedKeysOf(records)).toEqual([5]);
  });

  it('does nothing when the clicked index is out of bounds', () => {
    const { datatable, records } = createDatatable(3);
    const { handleCheckboxClick } = useTableSelection(datatable);

    handleCheckboxClick(9, click());

    expect(selectedKeysOf(records)).toEqual([]);
  });

  it('exposes the selection as a map indexed by row position', () => {
    const { datatable } = createDatatable(4, [2, 4]);
    const { rowSelection } = useTableSelection(datatable);

    expect(rowSelection.value).toEqual({
      1: true,
      3: true,
    });
  });

  it('applies a selection coming from the table component', () => {
    const { datatable, records } = createDatatable(4, [1]);
    const { handleRowSelectionChange } = useTableSelection(datatable);

    handleRowSelectionChange({
      1: true,
      2: true,
    });

    expect(selectedKeysOf(records)).toEqual([2, 3]);
  });

  it('keeps the current selection when the component sends nothing', () => {
    const { datatable, records } = createDatatable(4, [1]);
    const { handleRowSelectionChange } = useTableSelection(datatable);

    handleRowSelectionChange(undefined);

    expect(selectedKeysOf(records)).toEqual([1]);
  });

  it('deselects every record', () => {
    const { datatable, records } = createDatatable(4, [1, 2, 3]);
    const { deselectAll } = useTableSelection(datatable);

    deselectAll();

    expect(selectedKeysOf(records)).toEqual([]);
  });
});
