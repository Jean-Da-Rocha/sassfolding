<script setup lang="ts">
type Props = {} & /* @vue-ignore */ MeterGroupProps;

defineProps<Props>();

const theme = ref<MeterGroupPassThroughOptions>({
  label: `inline-flex items-center gap-2`,
  labelIcon: `text-base w-4 h-4`,
  labelList: `flex flex-wrap m-0 p-0 list-none
        p-horizontal:gap-4 p-vertical:flex-col p-vertical:gap-1 p-vertical:items-start`,
  labelMarker: `inline-flex w-2 h-2 rounded-full`,
  labelText: ``,
  meter: `p-horizontal:first-of-type:rounded-s-md p-horizontal:last-of-type:rounded-e-md
        p-vertical:first-of-type:rounded-ss-md p-vertical:first-of-type:rounded-se-md
        p-vertical:last-of-type:rounded-ee-md p-vertical:last-of-type:rounded-es-md`,
  meters: `flex bg-surface-200 dark:bg-surface-700 rounded-md p-horizontal:h-2 p-vertical:flex-col p-vertical:w-2 p-vertical:h-full`,
  root: `flex gap-4 p-horizontal:flex-col p-vertical:flex-row`,
});
</script>

<template>
  <MeterGroup
    :pt="theme"
    :pt-options="{
      mergeProps: ptViewMerge,
    }"
    unstyled
  >
    <template v-for="(_, slotName) in $slots" #[slotName]="slotProps">
      <slot :name="slotName" v-bind="slotProps ?? {}" />
    </template>
  </MeterGroup>
</template>
