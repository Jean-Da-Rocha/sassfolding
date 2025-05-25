<script setup lang="ts">
type Props = {} & /* @vue-ignore */ InplaceProps;

defineProps<Props>();

const theme = ref<InplacePassThroughOptions>({
  content: `block`,
  display: `inline-block cursor-pointer border border-transparent px-3 py-2 rounded-md p-disabled:pointer-events-none
        hover:bg-surface-100 dark:hover:bg-surface-800 hover:text-surface-800 dark:hover:text-surface-0
        focus-visible:outline focus-visible:outline-1 focus-visible:outline-offset-2 focus-visible:outline-primary
        transition-colors duration-200`,
  root: ``,
});
</script>

<template>
  <Inplace
    :pt="theme"
    :pt-options="{
      mergeProps: ptViewMerge,
    }"
    unstyled
  >
    <template v-for="(_, slotName) in $slots" #[slotName]="slotProps">
      <slot :name="slotName" v-bind="slotProps ?? {}" />
    </template>
  </Inplace>
</template>
