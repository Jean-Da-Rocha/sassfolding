import type { RouteName } from 'hybridly';

export type NavigationType = {
  /**
   * Heroicon displayed on the left of the sidebar link.
   */
  icon: Component;
  /**
   * The name of the navigation link.
   */
  navigationLinkName: string;
  /**
   * The Laravel route name. For instance: 'users.index'.
   */
  routeName?: RouteName;
};
