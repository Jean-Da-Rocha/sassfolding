<script setup lang="ts">
import type { PageState } from 'primevue/paginator';

import { breakpointsTailwind } from '@vueuse/core';

defineProps<{ paginator: Paginator }>();

const perPageOptions = [5, 10, 25, 50, 100];

const breakpoints = useBreakpoints(breakpointsTailwind);

// https://tailwind.primevue.org/paginator/#template
const template: Record<keyof typeof breakpointsTailwind, string> = {
  '2xl': 'FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown',
  'lg': 'FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink',
  'md': 'FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink',
  'sm': 'PrevPageLink CurrentPageReport NextPageLink',
  'xl': 'FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown',
};

const paginatorResponsiveTemplate = computed<string>(() => {
  const activeBreakpoint = breakpoints.active().value;

  // Edge case where screen width is lesser than the 'sm' breakpoints.
  if (activeBreakpoint === '') {
    return 'PrevPageLink CurrentPageReport NextPageLink';
  }

  return template[breakpoints.active().value as keyof typeof breakpointsTailwind];
});

function limitRecordsPerPage(perPageLimit: number): void {
  router.reload({
    transformUrl: {
      query: {
        page: undefined,
        per_page: perPageLimit,
      },
    },
  });
}

function changePage(pageState: PageState): void {
  const { page, rows } = pageState;

  router.reload({
    transformUrl: {
      query: {
        page: page + 1, // We must add + 1 because the page is zero-based indexed.
        per_page: rows,
      },
    },
  });
}
</script>

<template :paginator="paginator">
  <PrimeVuePaginator
    :always-show="true"
    :rows="paginator.meta.per_page"
    :rows-per-page-options="perPageOptions"
    :template="paginatorResponsiveTemplate"
    :total-records="paginator.meta.total"
    class="mt-5"
    @page="(event: PageState): void => changePage(event)"
    @update:rows="(value: number): void => limitRecordsPerPage(value)"
  />
</template>
