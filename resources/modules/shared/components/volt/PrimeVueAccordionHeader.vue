<script setup lang="ts">
type Props = {} & /* @vue-ignore */ AccordionHeaderProps;

defineProps<Props>();

// Workaround to solve Volt UI bad typing.
const parentInstance = inject('$parentInstance') as ComponentPublicInstance<{ active: boolean }>;

const theme = ref<AccordionHeaderPassThroughOptions>({
  root: `cursor-pointer disabled:pointer-events-none disabled:opacity-60 flex items-center justify-between p-[1.125rem] font-semibold
        bg-surface-0 dark:bg-surface-900
        text-surface-500 dark:text-surface-400
        hover:text-surface-700 dark:hover:text-surface-0
        p-active:text-surface-700 dark:p-active:text-surface-0
        transition-colors duration-200
        focus-visible:outline focus-visible:outline-1 focus-visible:outline-offset-[-1px] focus-visible:outline-primary`,
});
</script>

<template>
  <AccordionHeader
    :pt="theme"
    :pt-options="{
      mergeProps: ptViewMerge,
    }"
    unstyled
  >
    <template #toggleicon>
      <HeroiconsChevronDown v-if="parentInstance.active" />
      <HeroiconsChevronUp v-else />
    </template>
    <slot />
  </AccordionHeader>
</template>
