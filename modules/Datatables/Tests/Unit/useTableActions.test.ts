import { describe, expect, it, vi } from 'vitest';

type ActionOptions = {
  readonly confirm?: boolean;
  readonly confirmMessage?: string | null;
};

function bulkAction(name: string, options: ActionOptions = {}) {
  return {
    execute: vi.fn(),
    label: name,
    metadata: {
      color: null,
      confirm: options.confirm ?? false,
      confirm_message: options.confirmMessage ?? null,
      icon: null,
    },
    name,
  };
}

function recordAction(name: string, options: ActionOptions = {}) {
  return {
    execute: vi.fn(),
    label: name,
    metadata: {
      color: 'error',
      confirm: options.confirm ?? false,
      confirm_message: options.confirmMessage ?? null,
      icon: 'i-lucide-trash-2',
    },
    name,
  };
}

function createDatatable(options: {
  bulkActions?: ReturnType<typeof bulkAction>[];
  inlineActions?: unknown[];
  recordActions?: ReturnType<typeof recordAction>[];
} = {}) {
  return {
    bulkActions: options.bulkActions ?? [],
    inlineActions: options.inlineActions ?? [],
    records: [{ actions: options.recordActions ?? [] }],
  } as unknown as Datatable<Record<string, any>>;
}

describe('useTableActions', () => {
  it('reports whether the table has inline and bulk actions', () => {
    const { hasBulkActions, hasInlineActions } = useTableActions(
      createDatatable({
        bulkActions: [bulkAction('delete')],
        inlineActions: [{}],
      }),
      vi.fn(),
    );

    expect(hasBulkActions.value).toBe(true);
    expect(hasInlineActions.value).toBe(true);
  });

  it('reports no action on an empty table', () => {
    const { hasBulkActions, hasInlineActions } = useTableActions(createDatatable(), vi.fn());

    expect(hasBulkActions.value).toBe(false);
    expect(hasInlineActions.value).toBe(false);
  });

  it('runs a bulk action straight away when no confirmation is required', () => {
    const action = bulkAction('export');
    const requestConfirmation = vi.fn();
    const { executeBulkAction } = useTableActions(createDatatable({ bulkActions: [action] }), requestConfirmation);

    executeBulkAction(action as never);

    expect(action.execute).toHaveBeenCalledOnce();
    expect(requestConfirmation).not.toHaveBeenCalled();
  });

  it('asks for confirmation before running a destructive bulk action', () => {
    const action = bulkAction('delete', {
      confirm: true,
      confirmMessage: 'Delete them all?',
    });
    const requestConfirmation = vi.fn();
    const { executeBulkAction } = useTableActions(createDatatable({ bulkActions: [action] }), requestConfirmation);

    executeBulkAction(action as never);

    expect(action.execute).not.toHaveBeenCalled();
    expect(requestConfirmation).toHaveBeenCalledWith('Delete them all?', expect.any(Function));
  });

  it('runs the action once the confirmation callback is invoked', () => {
    const action = bulkAction('delete', { confirm: true });
    let confirm: (() => void) | undefined;
    const { executeBulkAction } = useTableActions(
      createDatatable({ bulkActions: [action] }),
      (_message, onConfirm) => (confirm = onConfirm),
    );

    executeBulkAction(action as never);
    confirm?.();

    expect(action.execute).toHaveBeenCalledOnce();
  });

  it('maps record actions to dropdown entries carrying their metadata', () => {
    const action = recordAction('delete');
    const { getRowActions } = useTableActions(createDatatable({ recordActions: [action] }), vi.fn());

    expect(getRowActions(0)).toEqual([
      expect.objectContaining({
        color: 'error',
        icon: 'i-lucide-trash-2',
        label: 'delete',
      }),
    ]);
  });

  it('runs a record action through its own execute', () => {
    const action = recordAction('edit');
    const { getRowActions } = useTableActions(createDatatable({ recordActions: [action] }), vi.fn());

    getRowActions(0)[0]!.onSelect?.({} as never);

    expect(action.execute).toHaveBeenCalledOnce();
  });

  it('returns no action for a row that does not exist', () => {
    const { getRowActions } = useTableActions(createDatatable(), vi.fn());

    expect(getRowActions(42)).toEqual([]);
  });

  it('fills the context menu with the actions of the right clicked row', () => {
    const action = recordAction('edit');
    const { contextMenuItems, onContextMenu } = useTableActions(
      createDatatable({ recordActions: [action] }),
      vi.fn(),
    );

    onContextMenu({} as Event, { index: 0 });

    expect(contextMenuItems.value).toHaveLength(1);
  });
});
