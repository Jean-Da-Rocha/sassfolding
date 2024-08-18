<script setup lang="ts">
// TODO: check that link is a route.
defineProps<{ isActive: boolean; link: string }>();
</script>

<template>
  <li>
    <RouterLink
      :class="[
        isActive
          ? 'bg-highlight hover:bg-highlight-emphasis'
          : 'text-surface-700 hover:bg-surface-100 dark:text-surface-0/80 dark:hover:bg-surface-700',
      ]"
      :href="link"
      class="flex cursor-pointer items-center rounded-md p-3 transition-colors duration-200 "
    >
      <span
        class="mr-2 flex size-6 items-center justify-center rounded-full border bg-gray-50 text-[0.625rem] font-medium"
        v-if="$slots.initials"
      >
        <slot name="initials" />
      </span>
      <span class="mr-2 size-6 shrink-0" v-if="$slots.icon">
        <slot name="icon" />
      </span>
      <span class="text-sm">
        <slot name="default" />
      </span>
    </RouterLink>
  </li>
</template>
