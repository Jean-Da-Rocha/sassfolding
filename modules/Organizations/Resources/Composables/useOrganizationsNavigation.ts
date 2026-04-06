export function useOrganizationsNavigation(): ModuleNavigationItem[] {
  return [
    {
      group: 'main',
      icon: 'i-lucide-building-2',
      label: 'Organizations',
      onClick: () => router.get(route('organizations.index')),
      order: 30,
      routeName: 'organizations.index',
    },
  ];
}
