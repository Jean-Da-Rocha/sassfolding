export function useSecurity(): {
  isUserAuthenticated: ComputedRef<boolean>;
  user: ComputedRef<App.Data.UserData | null>;
} {
  const user = useProperty<App.Data.UserData | null>('security.user');
  const isUserAuthenticated = computed<boolean>(() => Boolean(user.value));

  return { isUserAuthenticated, user };
}
