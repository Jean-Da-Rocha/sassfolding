export function useHybridlyLoading() {
  const isLoading = ref(false);

  const removeStartHook = registerHook('start', () => isLoading.value = true);
  const removeAfterHook = registerHook('after', () => isLoading.value = false);

  onUnmounted(() => {
    removeStartHook();
    removeAfterHook();
  });

  return isLoading;
}
