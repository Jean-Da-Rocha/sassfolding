<script setup lang="ts">
type Props = {} & /* @vue-ignore */ BreadcrumbProps;

defineProps<Props>();

const theme = ref<BreadcrumbPassThroughOptions>({
  item: ``,
  itemIcon: ``,
  itemLabel: ``,
  itemLink: `no-underline flex items-center gap-2 transition-colors duration-200 rounded-md
        text-surface-500 dark:text-surface-400 hover:text-surface-700 dark:hover:text-surface-0
        focus-visible:outline focus-visible:outline-1 focus-visible:outline-offset-2 focus-visible:outline-primary`,
  list: `m-0 p-0 list-none flex items-center flex-nowrap gap-2`,
  root: `bg-surface-0 dark:bg-surface-900 p-4 overflow-x-auto`,
  separator: `flex items-center text-surface-400 dark:text-surface-500`,
  separatorIcon: ``,
});
</script>

<template>
  <Breadcrumb
    :pt="theme"
    :pt-options="{
      mergeProps: ptViewMerge,
    }"
    unstyled
  >
    <template v-for="(_, slotName) in $slots" #[slotName]="slotProps">
      <slot :name="slotName" v-bind="slotProps ?? {}" />
    </template>
  </Breadcrumb>
</template>
