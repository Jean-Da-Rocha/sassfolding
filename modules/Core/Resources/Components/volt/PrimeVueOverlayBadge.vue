<script setup lang="ts">
type Props = {} & BadgeProps;

defineOptions({
  inheritAttrs: false,
});

defineProps<Props>();
</script>

<template>
  <div class="relative inline-flex">
    <slot />
    <PrimeVueBadge
      v-bind="$attrs" class="
        absolute end-0 top-0 m-0 origin-[100%_0] translate-x-[50%]
        translate-y-[-50%] outline-2 outline-surface-0
        dark:outline-surface-900
      "
    />
  </div>
</template>
