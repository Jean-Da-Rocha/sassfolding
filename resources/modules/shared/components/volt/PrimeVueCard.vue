<script setup lang="ts">
type Props = {} & /* @vue-ignore */ CardProps;

defineProps<Props>();

const theme = ref<CardPassThroughOptions>({
  body: `p-5 flex flex-col gap-2`,
  caption: `flex flex-col gap-2`,
  content: ``,
  footer: ``,
  header: ``,
  root: `flex flex-col rounded-xl
        bg-surface-0 dark:bg-surface-900
        text-surface-700 dark:text-surface-0
        shadow-md`,
  subtitle: `text-surface-500 dark:text-surface-400`,
  title: `font-medium text-xl`,
});
</script>

<template>
  <Card :pt="theme" :pt-options="{ mergeProps: ptViewMerge }" unstyled>
    <template v-for="(_, slotName) in $slots" #[slotName]="slotProps">
      <slot :name="slotName" v-bind="slotProps ?? {}" />
    </template>
  </Card>
</template>
