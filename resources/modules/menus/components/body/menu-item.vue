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
      <PrimeVueAvatar v-if="menuItem.initial && !menuItem.icon" :label="menuItem.initial" shape="circle" size="normal" />
      <span v-if="menuItem.icon" class="-mb-1 size-6 shrink-0">
        <component :is="menuItem.icon" />
      </span>
      <span class="text-sm ml-2">
        <slot />
      </span>
    </RouterLink>
  </li>
</template>
