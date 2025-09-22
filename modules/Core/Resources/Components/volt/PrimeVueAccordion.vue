<script setup lang="ts">
type Props = {} & /* @vue-ignore */ AccordionProps;

defineProps<Props>();

const theme = ref<AccordionPassThroughOptions>({
  root: ``,
});
</script>

<template>
  <Accordion
    :pt="theme"
    :pt-options="{
      mergeProps: ptViewMerge,
    }"
    unstyled
  >
    <slot />
  </Accordion>
</template>
