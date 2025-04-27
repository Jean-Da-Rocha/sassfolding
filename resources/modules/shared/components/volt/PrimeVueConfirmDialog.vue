<script setup lang="ts">
import type { ConfirmDialogPassThroughOptions, ConfirmDialogProps } from 'primevue/confirmdialog';
import ConfirmDialog from 'primevue/confirmdialog';
import { ref } from 'vue';
import Button from './PrimeVuePrimaryButton.vue';
import SecondaryButton from './PrimeVueSecondaryButton.vue';
import { ptViewMerge } from './utils';

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
  <ConfirmDialog
    unstyled
    :pt="theme"
    :pt-options="{
      mergeProps: ptViewMerge,
    }"
  >
    <template #container="{ message, acceptCallback, rejectCallback }">
      <div class="flex items-center justify-between shrink-0 p-5">
        <span class="font-semibold text-xl">{{ message.header }}</span>
        <SecondaryButton variant="text" rounded autofocus @click="rejectCallback">
          <template #icon>
            <HeroiconsXMark />
          </template>
        </SecondaryButton>
      </div>
      <div class="overflow-y-auto pt-0 px-5 pb-5 flex items-center gap-4">
        <HeroiconsExclamationTriangle class="size-6" />
        {{ message.message }}
      </div>
      <div class="pt-0 px-5 pb-5 flex justify-end gap-2">
        <SecondaryButton :label="message.rejectProps.label" size="small" @click="rejectCallback" />
        <Button :label="message.acceptProps.label" size="small" @click="acceptCallback" />
      </div>
    </template>
  </ConfirmDialog>
</template>
