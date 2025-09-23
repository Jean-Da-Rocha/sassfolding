<script setup lang="ts">
type Props = {} & /* @vue-ignore */ RatingProps;

defineProps<Props>();

const theme = ref<RatingPassThroughOptions>({
  option: `inline-flex items-center cursor-pointer rounded-full
        p-focus-visible:outline p-focus-visible:outline-1 p-focus-visible:outline-offset-2 p-focus-visible:outline-primary`,
  root: `relative flex items-center gap-1 p-disabled:opacity-60 p-disabled:pointer-events-none p-readonly:pointer-events-none`,
});
</script>

<template>
  <Rating
    :pt="theme"
    :pt-options="{
      mergeProps: ptViewMerge,
    }"
    unstyled
  >
    <template #onicon="slotProps">
      <HeroiconsStarSolid
        class="text-primary h-4 w-4 text-base transition-colors duration-200" @click="(slotProps as any).toggleCallback"
      />
    </template>
    <template #officon="slotProps">
      <HeroiconsStar
        class="
          text-surface-500 h-4 w-4 text-base transition-colors duration-200
          dark:text-surface-400
        "
        @click="(slotProps as any).toggleCallback"
      />
    </template>
    <template v-for="(_, slotName) in $slots" #[slotName]="slotProps">
      <slot :name="slotName" v-bind="slotProps ?? {}" />
    </template>
  </Rating>
</template>
