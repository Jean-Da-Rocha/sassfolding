<script setup lang="ts">
type Props = {} & /* @vue-ignore */ PopoverProps;

defineProps<Props>();

const theme = ref<PopoverPassThroughOptions>({
  content: `p-3`,
  root: `mt-[10px] p-flipped:-mt-[10px] p-flipped:mb-[10px]
        bg-surface-0 dark:bg-surface-900 text-surface-700 dark:text-surface-0
        border border-surface-200 dark:border-surface-700
        rounded-md shadow-[0_4px_6px_-1px_rgba(0,0,0,0.1),0_2px_4px_-2px_rgba(0,0,0,0.1)]
        before:bottom-full before:left-[calc(1.25rem+var(--p-popover-arrow-left))] before:h-0 before:w-0 before:absolute before:pointer-events-none
        before:border-[10px] before:-ms-[10px] before:border-transparent before:border-b-surface-200 dark:before:border-b-surface-700
        after:bottom-full after:left-[calc(1.25rem+var(--p-popover-arrow-left))] after:h-0 after:w-0 after:absolute after:pointer-events-none
        after:border-[8px] after:-ms-[8px] after:border-transparent after:border-b-surface-0 dark:after:border-b-surface-900
        p-flipped:before:bottom-auto p-flipped:before:top-full p-flipped:after:bottom-auto p-flipped:after:top-full
        p-flipped:after:border-b-transparent p-flipped:after:border-t-surface-0 dark:p-flipped:after:border-t-surface-900
        p-flipped:before:border-b-transparent p-flipped:before:border-t-surface-200 dark:p-flipped:before:border-t-surface-700`,
  transition: {
    enterActiveClass: 'transition duration-120 ease-[cubic-bezier(0,0,0.2,1)]',
    enterFromClass: 'opacity-0 scale-y-75',
    leaveActiveClass: 'transition-opacity duration-100 ease-linear',
    leaveToClass: 'opacity-0',
  },
});

const popoverInstance = useTemplateRef<InstanceType<typeof Popover>>('popoverInstance');

defineExpose({
  hide: () => popoverInstance.value?.toggle(),
  show: (event: Event, target?: HTMLElement) => popoverInstance.value?.show(event, target),
  toggle: (event: Event, target?: HTMLElement) => popoverInstance.value?.toggle(event, target),
});
</script>

<template>
  <Popover ref="popoverInstance" unstyled :pt="theme" :pt-options="{ mergeProps: ptViewMerge }">
    <template v-for="(_, slotName) in $slots" #[slotName]="slotProps">
      <slot :name="slotName" v-bind="slotProps ?? {}" />
    </template>
  </Popover>
</template>
