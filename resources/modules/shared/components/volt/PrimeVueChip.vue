<script setup lang="ts">
type Props = {} & /* @vue-ignore */ ChipProps;

defineProps<Props>();

const theme = ref<ChipPassThroughOptions>({
  icon: `text-surface-800 dark:text-surface-0 text-base w-4 h-4`,
  image: `rounded-full w-8 h-8 -ms-2`,
  root: `inline-flex items-center rounded-2xl gap-2 px-3 py-2
        bg-surface-100 dark:bg-surface-800
        text-surface-800 dark:text-surface-0
        has-[img]:pt-1 has-[img]:pb-1
        p-removable:pe-2`,
});
</script>

<template>
  <Chip
    :pt="theme"
    :pt-options="{
      mergeProps: ptViewMerge,
    }"
    unstyled
  >
    <template #removeicon="{ removeCallback, keydownCallback }">
      <HeroiconsXCircle
        class="
          h-4 w-4 cursor-pointer rounded-full text-base text-surface-800
          focus-visible:outline focus-visible:outline-offset-2
          focus-visible:outline-primary
          dark:text-surface-0
        "
        @click="removeCallback"
        @keydown="keydownCallback"
      />
    </template>
    <template v-for="(_, slotName) in $slots" #[slotName]="slotProps">
      <slot :name="slotName" v-bind="slotProps ?? {}" />
    </template>
  </Chip>
</template>
