import type { RouteName } from 'hybridly';

export type NavigationType = {
  /**
   * Prime icon displayed on the left of the sidebar link.
   */
  icon: string;
  /**
   * The name of the navigation link.
   */
  navigationLinkName: string;
  /**
   * The Laravel route name. For instance: 'users.index'.
   */
  routeName?: RouteName;
};
