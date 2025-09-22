<script setup lang="ts">
type Props = {} & /* @vue-ignore */ SelectProps;

defineProps<Props>();

const theme = ref<SelectPassThroughOptions>({
  dropdown: `flex items-center justify-center shrink-0 bg-transparent
        text-surface-400 w-10 rounded-e-md`,
  emptyMessage: `px-3 py-2`,
  header: `pt-2 pb-1 px-4`,
  label: `block whitespace-nowrap overflow-hidden flex-auto w-[1%]
        py-2 px-3 overflow-ellipsis
        p-clearable:pe-7 p-empty:overflow-hidden p-empty:opacity-0 p-editable:cursor-default
        text-surface-700 dark:text-surface-0 bg-transparent border-none outline-none
        p-placeholder:text-surface-500 dark:p-placeholder:text-surface-400
        p-disabled:text-surface-500 dark:p-disabled:text-surface-400
        p-small:text-sm p-small:px-[0.625rem] p-small:py-[0.375rem]
        p-large:text-lg p-large:px-[0.875rem] p-large:py-[0.625rem]`,
  list: `m-0 p-1 list-none gap-[2px] flex flex-col`,
  listContainer: `overflow-auto`,
  option: `cursor-pointer font-normal whitespace-nowrap relative overflow-hidden flex items-center
        px-3 py-2 border-none text-surface-700 dark:text-surface-0 bg-transparent rounded-sm
        p-focus:bg-surface-100 dark:p-focus:bg-surface-800 p-focus:text-surface-800 dark:p-focus:text-surface-0
        p-selected:bg-highlight p-focus:p-selected:bg-highlight-emphasis
        transition-colors duration-200`,
  optionBlankIcon: ``,
  optionCheckIcon: `relative -ms-[0.375rem] me-[0.375rem] text-surface-700 dark:text-surface-0`,
  optionGroup: `m-0 px-3 py-2 bg-transparent text-surface-500 dark:text-surface-400 font-semibold`,
  optionGroupLabel: ``,
  optionLabel: ``,
  overlay: `absolute top-0 left-0 rounded-md p-portal-self:min-w-full
        bg-surface-0 dark:bg-surface-900
        border border-surface-200 dark:border-surface-700
        text-surface-700 dark:text-surface-0
        shadow-[0_4px_6px_-1px_rgba(0,0,0,0.1),0_2px_4px_-2px_rgba(0,0,0,0.1)]`,
  pcFilter: {
    root: `w-full appearance-none rounded-md outline-hidden
            bg-surface-0 dark:bg-surface-950
            text-surface-700 dark:text-surface-0
            placeholder:text-surface-500 dark:placeholder:text-surface-400
            border border-surface-300 dark:border-surface-700
            enabled:hover:border-surface-400 dark:enabled:hover:border-surface-600
            enabled:focus:border-primary
            disabled:bg-surface-200 disabled:text-surface-500
            dark:disabled:bg-surface-700 dark:disabled:text-surface-400
            ps-3 pe-10 py-2 p-fluid:w-full
            transition-colors duration-200 shadow-[0_1px_2px_0_rgba(18,18,23,0.05)]`,
  },
  pcFilterContainer: {
    root: `relative`,
  },
  pcFilterIconContainer: {
    root: `absolute top-1/2 -mt-2 leading-none end-3 z-1`,
  },
  root: `inline-flex cursor-pointer relative select-none rounded-md p-fluid:flex
        bg-surface-0 dark:bg-surface-950
        border border-surface-300 hover:border-surface-400 dark:border-surface-700 dark:hover:border-surface-600
        p-focus:border-primary
        p-filled:bg-surface-50 dark:p-filled:bg-surface-800
        p-invalid:border-red-400 dark:p-invalid:border-red-300
        p-disabled:bg-surface-200 p-disabled:text-surface-500 dark:p-disabled:bg-surface-700 dark:p-disabled:text-surface-400 p-disabled:pointer-events-none
        shadow-[0_1px_2px_0_rgba(18,18,23,0.05)]
        transition-colors duration-200`,
  transition: {
    enterActiveClass: 'transition duration-120 ease-[cubic-bezier(0,0,0.2,1)]',
    enterFromClass: 'opacity-0 scale-y-75',
    leaveActiveClass: 'transition-opacity duration-100 ease-linear',
    leaveToClass: 'opacity-0',
  },
  virtualScroller: ``,
});
</script>

<template>
  <Select :pt="theme" :pt-options="{ mergeProps: ptViewMerge }" unstyled>
    <template #dropdownicon>
      <HeroiconsChevronDown />
    </template>
    <template #loadingicon>
      <HeroiconsArrowPath class="animate-spin" />
    </template>
    <template #filtericon>
      <HeroiconsMagnifyingGlass class="text-surface-400" />
    </template>
    <template #clearicon>
      <HeroiconsXMark class="absolute end-10 top-1/2 -mt-2 text-surface-400" />
    </template>
    <template v-for="(_, slotName) in $slots" #[slotName]="slotProps">
      <slot :name="slotName" v-bind="slotProps ?? {}" />
    </template>
  </Select>
</template>
