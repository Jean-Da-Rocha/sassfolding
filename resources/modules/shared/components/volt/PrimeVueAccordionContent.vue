<script setup lang="ts">
type Props = {} & /* @vue-ignore */ AccordionContentProps;

defineProps<Props>();

const theme = ref<AccordionContentPassThroughOptions>({
  content: `bg-surface-0 dark:bg-surface-900 text-surface-700 dark:text-surface-0 pt-0 px-[1.125rem] pb-[1.125rem]`,
  root: `flex flex-col`,
  transition: {
    enterActiveClass: 'overflow-hidden transition-[max-height] duration-1000 ease-[cubic-bezier(0.42,0,0.58,1)]',
    enterFromClass: 'max-h-0',
    enterToClass: 'max-h-[1000px]',
    leaveActiveClass: 'overflow-hidden transition-[max-height] duration-[450ms] ease-[cubic-bezier(0,1,0,1)]',
    leaveFromClass: 'max-h-[1000px]',
    leaveToClass: 'max-h-0',
  },
});
</script>

<template>
  <AccordionContent
    :pt="theme"
    :pt-options="{
      mergeProps: ptViewMerge,
    }"
    unstyled
  >
    <slot />
  </AccordionContent>
</template>
