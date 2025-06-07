<script setup lang="ts">
type Props = {} & /* @vue-ignore */ StepPanelProps;

defineProps<Props>();

const theme = ref<StepPanelPassThroughOptions>({
  content: `w-full ps-4`,
  root: `bg-surface-0 dark:bg-surface-900 text-surface-700 dark:text-surface-0 p-vertical:flex p-vertical:flex-auto`,
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
  <StepPanel
    :pt="theme"
    :pt-options="{
      mergeProps: ptViewMerge,
    }"
    unstyled
  >
    <template v-for="(_, slotName) in $slots" #[slotName]="slotProps">
      <slot :name="slotName" v-bind="slotProps ?? {}" />
    </template>
  </StepPanel>
</template>
