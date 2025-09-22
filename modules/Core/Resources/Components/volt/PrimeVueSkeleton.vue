<script setup lang="ts">
type Props = {} & /* @vue-ignore */ SkeletonProps;

defineProps<Props>();

const theme = ref<SkeletonPassThroughOptions>({
  root: `overflow-hidden bg-surface-200 dark:bg-surface-700 animate-pulse rounded-md p-circle:rounded-full`,
});
</script>

<template>
  <Skeleton
    :pt="theme"
    :pt-options="{
      mergeProps: ptViewMerge,
    }"
    unstyled
  />
</template>
