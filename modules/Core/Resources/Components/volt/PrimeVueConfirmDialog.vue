<script setup lang="ts">
type Props = {} & /* @vue-ignore */ ConfirmDialogProps;

defineProps<Props>();

const theme = ref<ConfirmDialogPassThroughOptions>({
  mask: `bg-black/50 fixed top-0 start-0 w-full h-full`,
  root: `max-h-[90%] max-w-screen rounded-xl
        border border-surface-200 dark:border-surface-700
        bg-surface-0 dark:bg-surface-900
        text-surface-700 dark:text-surface-0 shadow-lg`,
  transition: {
    enterActiveClass: 'transition-all duration-150 ease-[cubic-bezier(0,0,0.2,1)]',
    enterFromClass: 'opacity-0 scale-75',
    leaveActiveClass: 'transition-all duration-150 ease-[cubic-bezier(0.4,0,0.2,1)]',
    leaveToClass: 'opacity-0 scale-75',
  },
});
</script>

<template>
  <ConfirmDialog :pt="theme" :pt-options="{ mergeProps: ptViewMerge }" unstyled>
    <template #container="{ message, acceptCallback, rejectCallback }">
      <div class="flex shrink-0 items-center justify-between p-5">
        <span class="text-xl font-semibold">{{ message.header }}</span>
        <PrimeVueSecondaryButton autofocus rounded variant="text" @click="rejectCallback">
          <template #icon>
            <HeroiconsXMark />
          </template>
        </PrimeVueSecondaryButton>
      </div>
      <div class="flex items-center gap-4 overflow-y-auto px-5 pt-0 pb-5">
        <HeroiconsExclamationTriangle class="size-6" />
        {{ message.message }}
      </div>
      <div class="flex justify-end gap-2 px-5 pt-0 pb-5">
        <PrimeVueSecondaryButton :label="message.rejectProps.label" size="small" @click="rejectCallback" />
        <PrimeVueDangerButton :label="message.acceptProps.label" size="small" @click="acceptCallback" />
      </div>
    </template>
  </ConfirmDialog>
</template>
