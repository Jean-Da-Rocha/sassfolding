export function useCoreNavigation(): ModuleNavigationItem[] {
  return [
    {
      group: 'main',
      icon: 'i-lucide-house',
      label: 'Home',
      order: 0,
    },
    {
      badge: '4',
      group: 'main',
      icon: 'i-lucide-inbox',
      label: 'Inbox',
      order: 10,
    },
    {
      group: 'main',
      icon: 'i-lucide-settings',
      label: 'Settings',
      order: 900,
    },
  ];
}
