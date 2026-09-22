import { describe, expect, it, vi } from 'vitest';

describe('useTableConfirmation', () => {
  it('starts with no pending action and a closed modal', () => {
    const { confirmModal, pendingAction } = useTableConfirmation();

    expect(confirmModal.value).toBe(false);
    expect(pendingAction.value).toBeNull();
  });

  it('opens the modal and stores the pending action', () => {
    const { confirmModal, pendingAction, requestConfirmation } = useTableConfirmation();
    const onConfirm = vi.fn();

    requestConfirmation('Delete this user?', onConfirm);

    expect(confirmModal.value).toBe(true);
    expect(pendingAction.value?.message).toBe('Delete this user?');
    expect(onConfirm).not.toHaveBeenCalled();
  });

  it('runs the pending action and closes the modal on confirmation', () => {
    const { confirmModal, executeConfirmedAction, pendingAction, requestConfirmation } = useTableConfirmation();
    const onConfirm = vi.fn();

    requestConfirmation(undefined, onConfirm);
    executeConfirmedAction();

    expect(onConfirm).toHaveBeenCalledOnce();
    expect(confirmModal.value).toBe(false);
    expect(pendingAction.value).toBeNull();
  });

  it('does nothing when confirming without a pending action', () => {
    const { confirmModal, executeConfirmedAction } = useTableConfirmation();

    expect(() => executeConfirmedAction()).not.toThrow();
    expect(confirmModal.value).toBe(false);
  });
});
