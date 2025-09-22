<script setup lang="ts">
type Props = {} & /* @vue-ignore */ StepListProps;

defineProps<Props>();

const theme = ref<StepListPassThroughOptions>({
  root: `relative flex justify-between items-center m-0 p-0 list-none overflow-x-auto`,
});
</script>

<template>
  <StepList
    :pt="theme"
    :pt-options="{
      mergeProps: ptViewMerge,
    }"
    unstyled
  >
    <slot />
  </StepList>
</template>
