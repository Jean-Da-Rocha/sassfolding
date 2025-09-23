export function useSecurity(): {
  isUserAuthenticated: ComputedRef<boolean>;
  user: ComputedRef<Modules.Users.Data.UserData | null>;
} {
  const user = useProperty<Modules.Users.Data.UserData | null>('security.user');
  const isUserAuthenticated = computed<boolean>(() => Boolean(user.value));

  return { isUserAuthenticated, user };
}
