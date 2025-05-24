<script setup lang="ts">
type Props = {} & /* @vue-ignore */ DataViewProps;

defineProps<Props>();

const theme = ref<DataViewPassThroughOptions>({
  content: `bg-surface-0 dark:bg-surface-900 text-surface-700 dark:text-surface-0`,
  emptyMessage: ``,
  footer: `py-3 px-4 border-t border-surface-200 dark:border-surface-700
        bg-surface-0 dark:bg-surface-900
        text-surface-700 dark:text-surface-0`,
  header: `py-3 px-4 border-b border-surface-200 dark:border-surface-700
        bg-surface-0 dark:bg-surface-900
        text-surface-700 dark:text-surface-0`,
  pcPaginator: {
    root: `flex items-center justify-center flex-wrap py-2 px-4 rounded-md gap-1
        bg-surface-0 dark:bg-surface-900 text-surface-700 dark:text-surface-0`,
  },
  root: `border-none`,
});
</script>

<template>
  <DataView
    :pt="theme"
    :pt-options="{
      mergeProps: ptViewMerge,
    }"
    unstyled
  >
    <template #paginatorcontainer="{ page, pageCount, pageLinks, changePageCallback, firstPageCallback, lastPageCallback, prevPageCallback, nextPageCallback }">
      <div class="flex flex-wrap items-center justify-center gap-2">
        <PrimeVueSecondaryButton :disabled="page === 0" rounded text @click="firstPageCallback">
          <template #icon>
            <HeroiconsChevronDoubleLeft />
          </template>
        </PrimeVueSecondaryButton>
        <PrimeVueSecondaryButton :disabled="page === 0" rounded text @click="prevPageCallback">
          <template #icon>
            <HeroiconsChevronLeft />
          </template>
        </PrimeVueSecondaryButton>
        <div
          class="
            hidden items-center justify-center gap-2
            sm:flex
          "
        >
          <PrimeVueSecondaryButton
            v-for="pageLink of pageLinks" :key="pageLink" class="
              h-10 min-w-10 shrink-0
            " :class="[{ 'bg-highlight!': page + 1 === pageLink }]" rounded :text="page + 1 !== pageLink" @click="() => changePageCallback(pageLink - 1)"
          >
            {{ pageLink }}
          </PrimeVueSecondaryButton>
        </div>
        <PrimeVueSecondaryButton :disabled="page === pageCount! - 1" rounded text @click="nextPageCallback">
          <template #icon>
            <HeroiconsChevronRight />
          </template>
        </PrimeVueSecondaryButton>
        <PrimeVueSecondaryButton :disabled="page === pageCount! - 1" rounded text @click="lastPageCallback">
          <template #icon>
            <HeroiconsChevronDoubleRight />
          </template>
        </PrimeVueSecondaryButton>
      </div>
    </template>
    <template v-for="(_, slotName) in $slots" #[slotName]="slotProps">
      <slot :name="slotName" v-bind="slotProps ?? {}" />
    </template>
  </DataView>
</template>
