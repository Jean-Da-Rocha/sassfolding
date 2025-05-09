<script setup lang="ts" generic="T extends Record<string, any>">
import { useConfirm } from 'primevue';

const props = defineProps<{ table: Table<T> }>();

const datatable = useTable(props, 'table');

const confirm = useConfirm();

function confirmDestructiveAction(route: string): void {
  confirm.require({
    accept: () => router.delete(route),
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

const search = datatable.bindFilter<string>('search', {
  transformUrl: { query: { page: undefined } },
});

const slots = useSlots();

function tableHasActions(): boolean {
  return datatable.inlineActions.length > 0
    || datatable.bulkActions.length > 0
    || ['edit-button', 'show-button', 'delete-button'].some(slotName => Boolean(slots[slotName]));
}
</script>

<template>
  <PrimeVueConfirmDialog class="w-[30rem]" />
  <TableLayout>
    <template #create-button>
      <slot name="create-button" />
    </template>
    <template #search-bar>
      <PrimeVueInputText
        v-if="datatable.filters.length > 0"
        v-model="search"
        fluid
        placeholder="Search"
        size="small"
        type="search"
      />
    </template>
    <TableHeader>
      <HeaderRow>
        <HeaderCell v-for="column in datatable.columns" :key="column.name">
          <template #default>
            {{ column.label }}
          </template>
          <template #sort>
            <span
              v-if="column.isSortable"
              class="
                ml-2 flex-none text-surface-700
                dark:text-surface-0
              "
              @click="column.toggleSort({ direction: column.isSorting('asc') ? 'desc' : 'asc' })"
            >
              <HeroiconsChevronUp v-if="column.isSorting('asc')" />
              <HeroiconsChevronDown v-else-if="column.isSorting('desc')" />
              <HeroiconsChevronUpDown v-else />
            </span>
          </template>
        </HeaderCell>
        <HeaderCellAction v-if="tableHasActions()">
          Actions
        </HeaderCellAction>
      </HeaderRow>
    </TableHeader>
    <TableBody>
      <NoRecordsAvailable
        v-if="datatable.records.length === 0"
        :colspan="tableHasActions() ? datatable.columns.length + 1 : datatable.columns.length"
      />
      <BodyRow v-for="{ key, value } in datatable.records" :key="key">
        <BodyCell v-for="column in datatable.columns" :key="column.name">
          {{ value(column) }}
        </BodyCell>
        <BodyCellAction v-if="tableHasActions()">
          <slot name="edit-button" :record-id="key" />
          <slot name="show-button" :record-id="key" />
          <slot :confirm-destructive-action="confirmDestructiveAction" name="delete-button" :record-id="key" />
        </BodyCellAction>
      </BodyRow>
    </TableBody>
    <template #pagination>
      <LengthAwarePaginator :paginator="datatable.paginator" />
    </template>
  </TableLayout>
</template>
