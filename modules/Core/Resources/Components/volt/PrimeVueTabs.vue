<script setup lang="ts">
type Props = {} & /* @vue-ignore */ TabsProps;

const props = defineProps<Props>();

const theme = ref<TabsPassThroughOptions>({
  root: `flex flex-col`,
});
</script>

<template>
  <Tabs
    :pt="theme"
    :pt-options="{
      mergeProps: ptViewMerge,
    }"
    unstyled
    :value="props.value"
  >
    <slot />
  </Tabs>
</template>
