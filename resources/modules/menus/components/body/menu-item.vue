<script setup lang="ts">
import type { NavigationType } from '@/modules/menus/types/navigation-type';

defineProps<{ menuItem: NavigationType }>();

const { current } = useRoute();
</script>

<template>
  <li>
    <RouterLink
      :class="[
        menuItem.routeName === current
          ? 'bg-highlight hover:bg-highlight-emphasis dark:bg-primary dark:text-primary-contrast dark:hover:bg-primary-emphasis'
          : 'text-surface-700 hover:bg-surface-100 dark:text-surface-0/80 dark:hover:bg-surface-700',
      ]"
      :href="menuItem.routeName ? route(menuItem.routeName) : '#'"
      class="flex cursor-pointer items-center rounded-md p-3 transition-colors duration-200 "
    >
      <span
        v-if="menuItem.initial && !menuItem.icon"
        class="mr-2 flex size-6 items-center justify-center rounded-full border bg-gray-50 text-[0.625rem] font-medium text-surface-700"
      >
        {{ menuItem.initial }}
      </span>
      <span v-if="menuItem.icon" class="-mb-1 size-6 shrink-0">
        <component :is="menuItem.icon" />
      </span>
      <span class="text-sm">
        <slot />
      </span>
    </RouterLink>
  </li>
</template>
