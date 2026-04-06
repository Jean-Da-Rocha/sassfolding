export function useTableConfirmation(): UseTableConfirmationReturn {
  const confirmModal = ref(false);
  const pendingAction = ref<PendingConfirmation | null>(null);

  const requestConfirmation = (message: string | undefined, onConfirm: () => void): void => {
    pendingAction.value = {
      message,
      onConfirm,
    };
    confirmModal.value = true;
  };

  const executeConfirmedAction = (): void => {
    pendingAction.value?.onConfirm();
    confirmModal.value = false;
    pendingAction.value = null;
  };

  return {
    confirmModal,
    executeConfirmedAction,
    pendingAction,
    requestConfirmation,
  };
}
