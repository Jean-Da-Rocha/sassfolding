<script setup lang="ts" generic="T extends Record<string, any>">
import { useConfirm } from 'primevue';

const props = defineProps<{ table: Table<T> }>();

const datatable = useTable(props, 'table');

const confirm = useConfirm();

function confirmAction(action: DatatableActionType): void {
  confirm.require({
    accept: () => action.execute(),
    acceptClass: 'p-button-sm',
    acceptProps: {
      label: 'Delete',
      severity: 'danger',
    },
    header: 'Danger Zone',
    message: 'Do you want to delete this record?',
    rejectClass: 'p-button-sm',
    rejectProps: {
      label: 'Cancel',
      severity: 'secondary',
    },
  });
}

provide('confirmAction', confirmAction);
</script>

<template>
  <PrimeVueConfirmDialog class="w-[30rem]" />

  <TableLayout>
    <TableHeader>
      <HeaderRow>
        <HeaderCell v-for="column in datatable.columns" :key="column.name">
          <template #default>
            {{ column.label }}
          </template>
          <template #sort>
            <span
              v-if="column.isSortable"
              class="ml-2 flex-none text-surface-700 dark:text-surface-0"
              @click="column.toggleSort({ direction: column.isSorting('asc') ? 'desc' : 'asc' })"
            >
              <i v-if="column.isSorting('asc')" class="pi pi-sort-up size-5" />
              <i v-else-if="column.isSorting('desc')" class="pi pi-sort-down size-5" />
              <i v-else class="pi pi-sort size-5" />
            </span>
          </template>
        </HeaderCell>
        <HeaderCellAction v-if="datatable.inlineActions.length > 0 || datatable.bulkActions.length > 0">
          Actions
        </HeaderCellAction>
      </HeaderRow>
    </TableHeader>
    <TableBody>
      <NoRecordsAvailable v-if="datatable.records.length === 0" :colspan="datatable.columns.length" />
      <BodyRow v-for="{ key, value, actions } in datatable.records" :key="key">
        <BodyCell v-for="column in datatable.columns" :key="column.name">
          {{ value(column) }}
        </BodyCell>
        <BodyCellAction v-if="actions.length > 0">
          <CallToAction v-for="action in actions" :key="action.name" :action="action" />
        </BodyCellAction>
      </BodyRow>
    </TableBody>
    <template #pagination>
      <LengthAwarePaginator :paginator="datatable.paginator" />
    </template>
  </TableLayout>
</template>
