const iconMap = {
  error: 'i-heroicons-x-circle',
  info: 'i-heroicons-information-circle',
  neutral: 'i-heroicons-chat-bubble-left',
  primary: 'i-heroicons-bell',
  secondary: 'i-heroicons-bell',
  success: 'i-heroicons-check-circle',
  warning: 'i-heroicons-exclamation-triangle',
} as const satisfies Record<Modules.Core.Enums.FlashMessage, string>;

const durationMap = {
  error: 8000,
  info: 5000,
  neutral: 5000,
  primary: 5000,
  secondary: 5000,
  success: 5000,
  warning: 6000,
} as const satisfies Record<Modules.Core.Enums.FlashMessage, number>;

export function useFlashToast(): void {
  const toast = useToast();
  const flash = useProperty<Modules.Core.Data.FlashData | null>('flash');

  watch(flash, (flashData) => {
    if (!flashData) {
      return;
    }

    (Object.entries(flashData) as [Modules.Core.Enums.FlashMessage, string | null][]).forEach(([severity, message]) => {
      if (message) {
        toast.add({
          color: severity as ToastProps['color'],
          duration: durationMap[severity],
          icon: iconMap[severity],
          title: message,
        });
      }
    });
  }, { immediate: true });
}
