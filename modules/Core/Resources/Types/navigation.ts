export type ModuleNavigationItem = NavigationMenuItem & {
  readonly routeName?: string;
  readonly order: number;
  readonly group: 'main' | 'footer';
};

export type UseNavigationReturn = {
  readonly mainItems: ComputedRef<NavigationMenuItem[]>;
  readonly footerItems: ComputedRef<NavigationMenuItem[]>;
  readonly pageTitle: ComputedRef<string>;
};
