<script setup lang="ts">
import type { NavigationType } from '@/modules/menus/types/navigation-type';
import type { NavigationResponse } from 'hybridly';

const props = defineProps<{ menuItem: NavigationType }>();

const { current } = useRoute();

function handleNavigation(): Promise<NavigationResponse> {
  if (!props.menuItem.routeName) {
    // Since Hybridly always prepend the base url to our navigation,
    // we just reload the router with the current url and with state preservation.
    return router.reload();
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
      <span class="pi !text-xl" :class="menuItem.icon" />
      <span class="text-xs mt-3 text-center break-words px-2">{{ menuItem.navigationLinkName }}</span>
    </RouterLink>
  </li>
</template>
