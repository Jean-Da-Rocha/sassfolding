import type { NavigationType } from '@/modules/menus/types/navigation-type';

export const navigation: NavigationType[] = [
  { icon: 'pi-calendar', navigationLinkName: 'Calendar' },
  { icon: 'pi-search', navigationLinkName: 'Search' },
  { icon: 'pi-users', navigationLinkName: 'Team', routeName: 'users.index' },
  { icon: 'pi-cog', navigationLinkName: 'Settings' },
];
