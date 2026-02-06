export function useCoreNavigation(): ModuleNavigationItem[] {
  return [
    {
      group: 'main',
      icon: 'heroicons-home',
      label: 'Home',
      order: 0,
    },
    {
      badge: '4',
      group: 'main',
      icon: 'heroicons-inbox',
      label: 'Inbox',
      order: 10,
    },
    {
      group: 'main',
      icon: 'heroicons-cog-6-tooth',
      label: 'Settings',
      order: 900,
    },
  ];
}
