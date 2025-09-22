<script setup lang="ts">
type Props = {} & /* @vue-ignore */ StepPanelsProps;

defineProps<Props>();

const theme = ref<StepPanelsPassThroughOptions>({
  root: `pt-[0.875rem] pb-[1.125rem] px-2`,
});
</script>

<template>
  <StepPanels
    :pt="theme"
    :pt-options="{
      mergeProps: ptViewMerge,
    }"
    unstyled
  >
    <slot />
  </StepPanels>
</template>
