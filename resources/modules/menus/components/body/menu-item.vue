<script setup lang="ts">
import type { NavigationType } from '@/modules/menus/types/navigation-type';

defineProps<{ menuItem: NavigationType }>();

const { current } = useRoute();
</script>

<template>
  <li>
    <RouterLink
      href="#"
      class="relative flex flex-col items-center justify-center w-16 h-16 hover:text-primary-600 dark:hover:text-primary-300"
      :class="[
        (menuItem.routeName && menuItem.routeName === current)
          ? 'text-primary dark:text-primary-400'
          : 'dark:text-surface-0',
      ]"
    >
      <span class="pi !text-xl" :class="menuItem.icon" />
      <span class="text-xs mt-3 text-center break-words px-2">{{ menuItem.navigationLinkName }}</span>
    </RouterLink>
  </li>
</template>
