<script setup lang="ts">
type Props = {} & /* @vue-ignore */ TabListProps;

defineProps<Props>();

const navButton = `!absolute flex-shrink-0 top-0 z-20 h-full flex items-center justify-center cursor-pointer
        bg-surface-0 dark:bg-surface-900 text-surface-500 dark:text-surface-400 hover:text-surface-700 dark:hover:text-surface-0 w-10
        shadow-[0px_0px_10px_50px_rgba(255,255,255,0.6)] dark:shadow-[0px_0px_10px_50px] dark:shadow-surface-900/50
        focus-visible:z-10 focus-visible:outline focus-visible:outline-1 focus-visible:outline-offset-[-1px] focus-visible:outline-primary
        transition-colors duration-200`;

const theme = ref<TabListPassThroughOptions>({
  activeBar: `z-10 block absolute -bottom-px h-px bg-primary transition-[left] duration-200 ease-[cubic-bezier(0.35,0,0.25,1)]`,
  content: `flex-grow
        p-scrollable:overflow-x-auto p-scrollable:overflow-y-hidden p-scrollable:overscroll-y-contain p-scrollable:overscroll-x-auto
        scroll-smooth [scrollbar-width:none]`,
  nextButton: `${navButton} end-0`,
  prevButton: `${navButton} start-0`,
  root: `flex relative`,
  tabList: `relative flex bg-surface-0 dark:bg-surface-900 border-b border-surface-200 dark:border-surface-700
        p-scrollable:overflow-hidden`,
});
</script>

<template>
  <TabList
    :pt="theme"
    :pt-options="{
      mergeProps: ptViewMerge,
    }"
    unstyled
  >
    <slot />
  </TabList>
</template>
