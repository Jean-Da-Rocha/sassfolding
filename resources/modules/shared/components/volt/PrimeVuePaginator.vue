<script setup lang="ts">
type Props = {} & /* @vue-ignore */ PaginatorProps;

defineProps<Props>();

const theme = ref<PaginatorPassThroughOptions>({
  root: `flex items-center justify-center flex-wrap py-2 px-4 rounded-md gap-1
        bg-surface-0 dark:bg-surface-900 text-surface-700 dark:text-surface-0`,
});
</script>

<template>
  <Paginator unstyled :pt="theme" :pt-options="{ mergeProps: ptViewMerge }">
    <template #container="{ page, pageCount, pageLinks, changePageCallback, firstPageCallback, lastPageCallback, prevPageCallback, nextPageCallback }">
      <div class="flex flex-wrap gap-2 items-center justify-center">
        <SecondaryButton text rounded :disabled="page === 0" @click="firstPageCallback">
          <template #icon>
            <HeroiconsChevronDoubleLeft />
          </template>
        </SecondaryButton>
        <SecondaryButton text rounded :disabled="page === 0" @click="prevPageCallback">
          <template #icon>
            <HeroiconsChevronLeft />
          </template>
        </SecondaryButton>
        <div class="items-center justify-center gap-2 hidden sm:flex">
          <SecondaryButton v-for="pageLink of pageLinks" :key="pageLink" :text="page + 1 !== pageLink" rounded class="shrink-0 min-w-10 h-10" :class="[{ 'bg-highlight!': page + 1 === pageLink }]" @click="() => changePageCallback(pageLink - 1)">
            {{ pageLink }}
          </SecondaryButton>
        </div>
        <SecondaryButton text rounded :disabled="page === pageCount! - 1" @click="nextPageCallback">
          <template #icon>
            <HeroiconsChevronRight />
          </template>
        </SecondaryButton>
        <SecondaryButton text rounded :disabled="page === pageCount! - 1" @click="lastPageCallback">
          <template #icon>
            <HeroiconsChevronDoubleRight />
          </template>
        </SecondaryButton>
      </div>
    </template>
    <template v-for="(_, slotName) in $slots" #[slotName]="slotProps">
      <slot :name="slotName" v-bind="slotProps ?? {}" />
    </template>
  </Paginator>
</template>
