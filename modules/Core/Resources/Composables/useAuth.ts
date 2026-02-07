type AuthState = {
  readonly isAuthenticated: ComputedRef<boolean>;
  readonly user: ComputedRef<Modules.Users.Data.UserData | null>;
};

export function useAuth(): AuthState {
  const user = useProperty<Modules.Users.Data.UserData | null>('authenticatedUser');
  const isAuthenticated = computed<boolean>(() => Boolean(user.value));

  return {
    isAuthenticated,
    user,
  };
}
