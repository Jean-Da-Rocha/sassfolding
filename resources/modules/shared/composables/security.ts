export function useSecurity(): {
  isUserAuthenticated: globalThis.ComputedRef<boolean>;
  user: globalThis.ComputedRef<App.Data.UserData | null>;
} {
  const user = useProperty('security.user');
  const isUserAuthenticated = computed(() => Boolean(user.value));

  return {
    isUserAuthenticated,
    user,
  };
}
