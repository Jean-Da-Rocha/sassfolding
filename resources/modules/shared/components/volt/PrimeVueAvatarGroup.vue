<script setup lang="ts">
type Props = {} & /* @vue-ignore */ AvatarGroupProps;

defineProps<Props>();

const theme = ref<AvatarGroupPassThroughOptions>({
  root: `flex items-center *:border-2 *:border-surface-200 dark:*:border-surface-700 *:-ms-3`,
});
</script>

<template>
  <AvatarGroup
    :pt="theme"
    :pt-options="{
      mergeProps: ptViewMerge,
    }"
    unstyled
  >
    <template v-for="(_, slotName) in $slots" #[slotName]="slotProps">
      <slot :name="slotName" v-bind="slotProps ?? {}" />
    </template>
  </AvatarGroup>
</template>
