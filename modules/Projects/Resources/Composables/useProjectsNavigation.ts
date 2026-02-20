export function useProjectsNavigation(): ModuleNavigationItem[] {
  return [
    {
      group: 'main',
      icon: 'i-lucide-folder-kanban',
      label: 'Projects',
      onClick: () => router.get(route('projects.index')),
      order: 40,
      routeName: 'projects.index',
    },
    {
      group: 'main',
      icon: 'i-lucide-list-checks',
      label: 'Tasks',
      onClick: () => router.get(route('tasks.index')),
      order: 50,
      routeName: 'tasks.index',
    },
  ];
}
