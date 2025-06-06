<script setup lang="ts">
type Props = {} & /* @vue-ignore */ TabPanelProps;

const props = defineProps<Props>();

const theme = ref<TabPanelPassThroughOptions>({
  root: ``,
});
</script>

<template>
  <TabPanel
    :pt="theme"
    :pt-options="{
      mergeProps: ptViewMerge,
    }"
    unstyled
    :value="props.value"
  >
    <slot />
  </TabPanel>
</template>
