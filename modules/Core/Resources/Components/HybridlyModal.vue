<script setup lang="ts">
type Props = {
  description?: string;
  title?: string;
};

withDefaults(defineProps<Props>(), {
  description: undefined,
  title: undefined,
});

const { close, show, unmount } = useDialog();

// show is Computed<boolean> from Hybridly
// Create a writable computed that syncs with Hybridly's dialog state
const isOpen = computed({
  get: () => show.value,
  set: (value: boolean) => {
    if (!value) {
      close();
    }
  },
});
</script>

<template>
  <UModal
    v-model:open="isOpen"
    :description="description"
    :title="title"
    @after:leave="unmount"
  >
    <template #content>
      <slot :close="close" />
    </template>
  </UModal>
</template>
