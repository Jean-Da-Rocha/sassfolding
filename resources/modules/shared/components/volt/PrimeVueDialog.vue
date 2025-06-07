<script setup lang="ts">
type Props = {} & /* @vue-ignore */ DialogProps;

defineProps<Props>();

const theme = ref<DialogPassThroughOptions>({
  content: `overflow-y-auto pt-0 px-5 pb-5 p-maximized:grow`,
  footer: `shrink-0 pt-0 px-5 pb-5 flex justify-end gap-2`,
  header: `flex items-center justify-between shrink-0 p-5`,
  headerActions: `flex items-center gap-2`,
  mask: `p-modal:bg-black/50 p-modal:fixed p-modal:top-0 p-modal:start-0 p-modal:w-full p-modal:h-full`,
  root: `max-h-[90%] max-w-screen rounded-xl
        border border-surface-200 dark:border-surface-700
        bg-surface-0 dark:bg-surface-900
        text-surface-700 dark:text-surface-0 shadow-lg
        p-maximized:w-screen p-maximized:h-screen p-maximized:top-0 p-maximized:start-0p-maximized: max-h-full p-maximized:rounded-none`,
  title: `font-semibold text-xl`,
  transition: {
    enterActiveClass: 'transition-all duration-150 ease-[cubic-bezier(0,0,0.2,1)]',
    enterFromClass: 'opacity-0 scale-75',
    leaveActiveClass: 'transition-all duration-150 ease-[cubic-bezier(0.4,0,0.2,1)]',
    leaveToClass: 'opacity-0 scale-75',
  },
});
</script>

<template>
  <Dialog
    :pt="theme"
    :pt-options="{
      mergeProps: ptViewMerge,
    }"
    unstyled
  >
    <template #closebutton="{ closeCallback }">
      <PrimeVueSecondaryButton autofocus rounded variant="text" @click="closeCallback">
        <template #icon>
          <HeroiconsXMark />
        </template>
      </PrimeVueSecondaryButton>
    </template>
    <template #maximizebutton="{ maximized, maximizeCallback }">
      <PrimeVueSecondaryButton autofocus rounded variant="text" @click="maximizeCallback">
        <template #icon>
          <HeroiconsArrowsPointingIn v-if="maximized" />
          <HeroiconsArrowsPointingOut v-else />
        </template>
      </PrimeVueSecondaryButton>
    </template>
    <template v-for="(_, slotName) in $slots" #[slotName]="slotProps">
      <slot :name="slotName" v-bind="slotProps ?? {}" />
    </template>
  </Dialog>
</template>
