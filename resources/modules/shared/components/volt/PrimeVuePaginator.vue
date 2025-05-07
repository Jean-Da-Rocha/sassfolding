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
  <Paginator :pt="theme" :pt-options="{ mergeProps: ptViewMerge }" unstyled>
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
      <div class="flex items-center gap-2">
        <PrimeVueSelect
          :model-value="rows"
          option-label="name"
          option-value="value"
          :options="perPageOptions"
          pt:dropdown="w-8"
          pt:label="pe-2"
          @change="(event) => rowChangeCallback(event.value)"
        />
      </div>
    </template>
    <template v-for="(_, slotName) in $slots" #[slotName]="slotProps">
      <slot :name="slotName" v-bind="slotProps ?? {}" />
    </template>
  </Paginator>
</template>
