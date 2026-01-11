export function useAuth(): {
  isAuthenticated: ComputedRef<boolean>;
  user: ComputedRef<Modules.Users.Data.UserData | null>;
} {
  const user = useProperty<Modules.Users.Data.UserData | null>('user');
  const isAuthenticated = computed<boolean>(() => Boolean(user.value));

  return { isAuthenticated, user };
}
