<script setup lang="ts">
type Props = {} & /* @vue-ignore */ StepItemProps;

defineProps<Props>();

const theme = ref<StepItemPassThroughOptions>({
  root: `flex flex-col flex-initial p-active:flex-auto last-of-type:*:last:ps-8`,
});
</script>

<template>
  <StepItem
    :pt="theme"
    :pt-options="{
      mergeProps: ptViewMerge,
    }"
    unstyled
  >
    <slot />
  </StepItem>
</template>
