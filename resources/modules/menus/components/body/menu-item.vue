<script setup lang="ts">
const props = defineProps<{ menuItem: AppNavigationType }>();

const { current } = useRoute();

/**
 * Reload the current page with state preservation to avoid losing applied filters,
 * sorts, etc., due to Hybridly prefixing the navigation URL with the app's base URL.
 */
function handleNavigation(): Promise<NavigationResponse> {
  if (!props.menuItem.routeName) {
    return router.reload({ preserveState: true });
  }

  return router.get(route(props.menuItem.routeName));
}
</script>

<template>
  <li>
    <RouterLink
      class="relative flex flex-col items-center justify-center w-16 h-16 hover:text-primary-600 dark:hover:text-primary-300 cursor-pointer"
      :class="[
        (menuItem.routeName && menuItem.routeName === current)
          ? 'text-primary dark:text-primary-400'
          : 'dark:text-surface-0',
      ]"
      method="get"
      as="button"
      @click.prevent="handleNavigation"
    >
      <component :is="menuItem.icon" class="size-6" />
      <span class="text-xs mt-3 text-center break-words px-2">{{ menuItem.navigationLinkName }}</span>
    </RouterLink>
  </li>
</template>
