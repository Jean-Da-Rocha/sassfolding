<script setup lang="ts">
type Props = {} & /* @vue-ignore */ SplitterProps;

defineProps<Props>();

const theme = ref<SplitterPassThroughOptions>({
  gutter: `grow-0 shrink-0 flex items-center justify-center z-10 bg-surface-200 dark:bg-surface-700
        p-horizontal:cursor-col-resize p-vertical:cursor-row-resize`,
  gutterHandle: `rounded-md bg-transparent
        focus-visible:outline focus-visible:outline-1 focus-visible:outline-offset-2 focus-visible:outline-primary
        transition-colors duration-200
        p-horizontal:h-[24px] p-horizontal:w-full p-vertical:w-[24px] p-vertical:h-full`,
  root: `flex flex-wrap
        border border-surface-200 dark:border-surface-700 rounded-md
        bg-surface-0 dark:bg-surface-900
        text-surface-700 dark:text-surface-0
        p-vertical:flex-col p-nested:grow p-nested:border-none
        *:grow *:overflow-hidden *:flex`,
});
</script>

<template>
  <Splitter
    :gutter-size="1"
    :pt="theme"
    :pt-options="{
      mergeProps: ptViewMerge,
    }"
    unstyled
  >
    <slot />
  </Splitter>
</template>
