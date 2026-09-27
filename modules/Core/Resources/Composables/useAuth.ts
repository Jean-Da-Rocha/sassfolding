type AuthState = {
  readonly user: ComputedRef<Modules.Users.Data.UserData | null>;
};

export function useAuth(): AuthState {
  const user = useProperty<Modules.Users.Data.UserData | null>('authenticatedUser');

  return {
    user,
  };
}
