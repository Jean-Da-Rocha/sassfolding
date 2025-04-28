<script setup lang="ts">
type Props = {} & /* @vue-ignore */ PaginatorProps;

defineProps<Props>();

const perPageOptions = ref<{ name: string; value: number }[]>([
  { name: '5', value: 5 },
  { name: '10', value: 10 },
  { name: '25', value: 25 },
  { name: '50', value: 50 },
  { name: '100', value: 100 },
]);

const theme = ref<PaginatorPassThroughOptions>({
  root: `flex items-center justify-center flex-wrap py-2 px-4 rounded-md gap-1
        bg-surface-0 dark:bg-surface-900 text-surface-700 dark:text-surface-0`,
});
</script>

<template>
  <Paginator unstyled :pt="theme" :pt-options="{ mergeProps: ptViewMerge }">
    <template
      #container="{
        changePageCallback,
        firstPageCallback,
        lastPageCallback,
        nextPageCallback,
        page,
        pageCount,
        pageLinks,
        prevPageCallback,
        rows,
        rowChangeCallback,
      }"
    >
      <div class="flex flex-wrap gap-2 items-center justify-center">
        <PrimeVueSecondaryButton text rounded :disabled="page === 0" @click="firstPageCallback">
          <template #icon>
            <HeroiconsChevronDoubleLeft />
          </template>
        </PrimeVueSecondaryButton>
        <PrimeVueSecondaryButton text rounded :disabled="page === 0" @click="prevPageCallback">
          <template #icon>
            <HeroiconsChevronLeft />
          </template>
        </PrimeVueSecondaryButton>
        <div class="items-center justify-center gap-2 hidden sm:flex">
          <PrimeVueSecondaryButton v-for="pageLink of pageLinks" :key="pageLink" :text="page + 1 !== pageLink" rounded class="shrink-0 min-w-10 h-10" :class="[{ 'bg-highlight!': page + 1 === pageLink }]" @click="() => changePageCallback(pageLink - 1)">
            {{ pageLink }}
          </PrimeVueSecondaryButton>
        </div>
        <PrimeVueSecondaryButton text rounded :disabled="page === pageCount! - 1" @click="nextPageCallback">
          <template #icon>
            <HeroiconsChevronRight />
          </template>
        </PrimeVueSecondaryButton>
        <PrimeVueSecondaryButton text rounded :disabled="page === pageCount! - 1" @click="lastPageCallback">
          <template #icon>
            <HeroiconsChevronDoubleRight />
          </template>
        </PrimeVueSecondaryButton>
      </div>
      <div class="flex gap-2 items-center">
        <PrimeVueSelect
          :model-value="rows"
          :options="perPageOptions"
          option-label="name"
          option-value="value"
          pt:label="pe-2"
          pt:dropdown="w-8"
          @change="(event) => rowChangeCallback(event.value)"
        />
      </div>
    </template>
    <template v-for="(_, slotName) in $slots" #[slotName]="slotProps">
      <slot :name="slotName" v-bind="slotProps ?? {}" />
    </template>
  </Paginator>
</template>
