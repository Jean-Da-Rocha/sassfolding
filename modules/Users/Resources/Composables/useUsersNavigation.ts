export function useUsersNavigation(): ModuleNavigationItem[] {
  return [
    {
      group: 'main',
      icon: 'heroicons-users',
      label: 'Users',
      onClick: () => router.get(route('users.index')),
      order: 20,
      routeName: 'users.index',
    },
  ];
}
