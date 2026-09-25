export function useNavigation(): UseNavigationReturn {
  const { current } = useRoute();

  const allItems: ModuleNavigationItem[] = [
    ...useCoreNavigation(),
    ...useUsersNavigation(),
  ].sort((a, b) => a.order - b.order);

  const withActiveState = computed<ModuleNavigationItem[]>(() =>
    allItems.map((item: ModuleNavigationItem) => ({
      ...item,
      active: item.routeName ? current.value === item.routeName : false,
    })),
  );

  const mainItems = computed<NavigationMenuItem[]>(() =>
    withActiveState.value.filter((item: ModuleNavigationItem) => item.group === 'main'),
  );

  const footerItems = computed<NavigationMenuItem[]>(() =>
    withActiveState.value.filter((item: ModuleNavigationItem) => item.group === 'footer'),
  );

  const pageTitle = computed(() => {
    const activeItem = withActiveState.value.find((item: ModuleNavigationItem) => item.active);

    return activeItem?.label ?? '';
  });

  return {
    footerItems,
    mainItems,
    pageTitle,
  };
}
