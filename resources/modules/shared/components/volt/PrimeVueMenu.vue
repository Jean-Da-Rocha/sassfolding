<script setup lang="ts">
type Props = {} & /* @vue-ignore */ MenuProps;

defineProps<Props>();

const theme = ref<MenuPassThroughOptions>({
  item: `p-disabled:opacity-60 p-disabled:pointer-events-none`,
  itemContent: `group transition-colors duration-200 rounded-sm text-surface-700 dark:text-surface-0
        p-focus:bg-surface-100 dark:p-focus:bg-surface-800 p-focus:text-surface-800 dark:p-focus:text-surface-0
        hover:bg-surface-100 dark:hover:bg-surface-800 hover:text-surface-800 dark:hover:text-surface-0`,
  itemIcon: `text-surface-400 dark:text-surface-500
        p-focus:text-surface-500 dark:p-focus:text-surface-400
        group-hover:text-surface-500 dark:group-hover:text-surface-400`,
  itemLabel: ``,
  itemLink: `cursor-pointer flex items-center no-underline overflow-hidden relative text-inherit
        px-3 py-2 gap-2 select-none outline-none`,
  list: `m-0 p-1 list-none outline-none flex flex-col gap-[2px]`,
  root: `bg-surface-0 dark:bg-surface-900
        text-surface-700 dark:text-surface-0
        border border-surface-200 dark:border-surface-700
        rounded-md min-w-52
        p-popup:shadow-[0_4px_6px_-1px_rgba(0,0,0,0.1),0_2px_4px_-2px_rgba(0,0,0,0.1)]`,
  separator: `border-t border-surface-200 dark:border-surface-700`,
  submenuLabel: `bg-transparent px-3 py-2 text-surface-500 dark:text-surface-400 font-semibold`,
  transition: {
    enterActiveClass: 'transition duration-120 ease-[cubic-bezier(0,0,0.2,1)]',
    enterFromClass: 'opacity-0 scale-y-75',
    leaveActiveClass: 'transition-opacity duration-100 ease-linear',
    leaveToClass: 'opacity-0',
  },
});

const menuInstance = useTemplateRef<InstanceType<typeof Menu>>('menuInstance');

defineExpose({
  toggle: (event: Event) => menuInstance.value?.toggle(event),
});
</script>

<template>
  <Menu
    ref="menuInstance"
    :pt="theme"
    :pt-options="{
      mergeProps: ptViewMerge,
    }"
    unstyled
  >
    <template v-for="(_, slotName) in $slots" #[slotName]="slotProps">
      <slot :name="slotName" v-bind="slotProps ?? {}" />
    </template>
  </Menu>
</template>
