<script setup lang="ts">
import type { BulkAction } from '../../../Datatables/Resources/Types/table';

type UserData = Modules.Users.Data.UserData;

const props = defineProps<{ users: Table<UserData> }>();

useHead({ title: 'Users Listing' });

const bulkActions: BulkAction<UserData>[] = [
  {
    color: 'error',
    icon: 'i-heroicons-trash',
    label: 'Delete Selected',
    onSelect: () => { /* delete */ },
  },
  {
    icon: 'i-heroicons-envelope',
    label: 'Send Email',
    onSelect: () => { /* open email modal */ },
  },
  {
    icon: 'i-heroicons-arrow-down-tray',
    label: 'Export CSV',
    onSelect: () => { /* download CSV */ },
  },
  {
    color: 'warning',
    icon: 'i-heroicons-no-symbol',
    label: 'Deactivate',
    onSelect: () => { /* bulk status change */ },
  },
];
</script>

<template layout="core::main">
  <ServerTable
    :actions="[
      { label: 'Edit', icon: 'i-heroicons-pencil-square', route: 'users.edit' },
      { label: 'Delete', icon: 'i-heroicons-trash', route: 'users.destroy', method: 'delete', color: 'error', confirm: true },
    ]"
    :bulk-actions="bulkActions"
    resource-name="user"
    selectable
    :table="props.users"
  >
    <template #create>
      <UButton
        icon="i-heroicons-plus"
        label="Create User"
        @click="router.get(route('users.create'))"
      />
    </template>

    <template #name-cell="{ row }">
      <div class="flex items-center gap-3">
        <UAvatar
          :alt="row.original.name"
          size="lg"
          :text="row.original.name_initial"
        />
        <span class="font-medium text-highlighted" v-text="row.original.name" />
      </div>
    </template>
  </ServerTable>
</template>
