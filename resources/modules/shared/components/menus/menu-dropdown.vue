<script setup lang="ts">
defineProps<{ title: string }>();
</script>

<template>
  <nav>
    <ul class="m-0 list-none px-4">
      <!-- TODO: add tailwind classes + dropdown animation + rotate -->
      <li>
        <div class="flex cursor-pointer items-center justify-between rounded-md p-3 text-surface-700 hover:bg-surface-100 dark:text-surface-0/80 dark:hover:bg-surface-700">
          <h2 class="text-sm font-bold uppercase">
            {{ title }}
          </h2>
          <HeroiconsChevronDown />
        </div>
        <slot />
      </li>
    </ul>
  </nav>
</template>
