import type { NavigationType } from '@/modules/menus/types/navigation-type';

export const navigation: NavigationType[] = [
  { icon: 'pi-calendar', navigationLinkName: 'Calendar' },
  { icon: 'pi-folder', navigationLinkName: 'My Files' },
  { icon: 'pi-users', navigationLinkName: 'Users', routeName: 'users.index' },
  { icon: 'pi-cog', navigationLinkName: 'Settings' },
];
