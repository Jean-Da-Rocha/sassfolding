<script setup lang="ts">
type Props = {} & /* @vue-ignore */ AccordionPanelProps;

const props = defineProps<Props>();

const theme = ref<AccordionPanelPassThroughOptions>({
  root: `flex flex-col border-b border-surface-200 dark:border-surface-700`,
});
</script>

<template>
  <AccordionPanel
    :pt="theme"
    :pt-options="{
      mergeProps: ptViewMerge,
    }"
    unstyled
    :value="props.value"
  >
    <slot />
  </AccordionPanel>
</template>
