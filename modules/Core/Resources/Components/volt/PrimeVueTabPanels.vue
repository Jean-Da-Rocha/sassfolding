<script setup lang="ts">
type Props = {} & /* @vue-ignore */ TabPanelsProps;

defineProps<Props>();

const theme = ref<TabPanelsPassThroughOptions>({
  root: `bg-surface-0 dark:bg-surface-900 text-surface-700 dark:text-surface-0
        pt-[0.875rem] pb-[1.125rem] px-[1.125rem] outline-none`,
});
</script>

<template>
  <TabPanels
    :pt="theme"
    :pt-options="{
      mergeProps: ptViewMerge,
    }"
    unstyled
  >
    <slot />
  </TabPanels>
</template>
