import type { RouteName } from 'hybridly';

export type NavigationType = {
  /**
   * Heroicon displayed on the left of the sidebar link.
   */
  icon?: Component;
  /**
   * Initials of the navigation link title. For instance: 'companies' → 'C'.
   */
  initial?: string;
  /**
   * The name of the navigation link.
   */
  navigationLinkName: string;
  /**
   * The Laravel route name. For instance: 'users.index'.
   */
  routeName?: RouteName;
};
