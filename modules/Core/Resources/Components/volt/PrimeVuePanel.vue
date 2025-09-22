<script setup lang="ts">
type Props = {} & /* @vue-ignore */ PanelProps;

defineProps<Props>();

const theme = ref<PanelPassThroughOptions>({
  content: `pt-0 pb-[1.125rem] px-[1.125rem] `,
  contentContainer: ``,
  footer: `pt-0 pb-[1.125rem] px-[1.125rem] `,
  header: `flex justify-between items-center p-[1.125rem] p-toggleable:py-[0.375rem] p-toggleable:px-[1.125rem]`,
  headerActions: `flex items-center gap-1`,
  root: `border border-surface-200 dark:border-surface-700 rounded-md
        bg-surface-0 dark:bg-surface-900
        text-surface-700 dark:text-surface-0`,
  title: `leading-none font-semibold`,
  transition: {
    enterActiveClass: 'overflow-hidden transition-[max-height] duration-1000 ease-[cubic-bezier(0.42,0,0.58,1)]',
    enterFromClass: 'max-h-0',
    enterToClass: 'max-h-[1000px]',
    leaveActiveClass: 'overflow-hidden transition-[max-height] duration-[450ms] ease-[cubic-bezier(0,1,0,1)]',
    leaveFromClass: 'max-h-[1000px]',
    leaveToClass: 'max-h-0',
  },
});
</script>

<template>
  <Panel
    :pt="theme"
    :pt-options="{
      mergeProps: ptViewMerge,
    }"
    unstyled
  >
    <template #togglebutton="{ collapsed, toggleCallback, keydownCallback }">
      <PrimeVueSecondaryButton rounded variant="text" @click="toggleCallback" @keydown="keydownCallback">
        <template #icon>
          <HeroiconsPlus v-if="collapsed" />
          <HeroiconsMinus v-else />
        </template>
      </PrimeVueSecondaryButton>
    </template>
    <template v-for="(_, slotName) in $slots" #[slotName]="slotProps">
      <slot :name="slotName" v-bind="slotProps ?? {}" />
    </template>
  </Panel>
</template>
