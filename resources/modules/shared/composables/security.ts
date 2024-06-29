import type { ComputedRef } from '@vue/runtime-core';

export function useSecurity(): {
  isUserAuthenticated: ComputedRef<boolean>;
  user: ComputedRef<App.Data.UserData | null>;
} {
  const user = useProperty('security.user');
  const isUserAuthenticated = computed(() => Boolean(user.value));

  return {
    isUserAuthenticated,
    user,
  };
}
