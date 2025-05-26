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
    <template #onicon="{ toggleCallback }">
      <HeroiconsStarSolid
        class="h-4 w-4 text-base text-primary transition-colors duration-200" @click="toggleCallback"
      />
    </template>
    <template #officon="{ toggleCallback }">
      <HeroiconsStar
        class="
          h-4 w-4 text-base text-surface-500 transition-colors duration-200
          dark:text-surface-400
        "
        @click="toggleCallback"
      />
    </template>
    <template v-for="(_, slotName) in $slots" #[slotName]="slotProps">
      <slot :name="slotName" v-bind="slotProps ?? {}" />
    </template>
  </Rating>
</template>
