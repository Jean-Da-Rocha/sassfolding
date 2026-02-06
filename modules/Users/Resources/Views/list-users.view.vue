<script setup lang="ts">
type UserData = Modules.Users.Data.UserData;

defineProps<{ users: Table<UserData> }>();

useHead({ title: 'Users Listing' });

const inlineActions = [
  {
    icon: 'i-heroicons-pencil-square',
    label: 'Edit',
    route: 'users.edit',
  },
  {
    color: 'error',
    confirm: true,
    icon: 'i-heroicons-trash',
    label: 'Delete',
    method: 'delete',
    route: 'users.destroy',
  },
] satisfies InlineAction<UserData>[];

const bulkActions = [
  {
    color: 'error',
    icon: 'i-heroicons-trash',
    label: 'Delete Selected',
    onSelect: () => {},
  },
  {
    icon: 'i-heroicons-envelope',
    label: 'Send Email',
    onSelect: () => {},
  },
  {
    icon: 'i-heroicons-arrow-down-tray',
    label: 'Export CSV',
    onSelect: () => {},
  },
  {
    color: 'warning',
    icon: 'i-heroicons-no-symbol',
    label: 'Deactivate',
    onSelect: () => {},
  },
] satisfies BulkAction<UserData>[];
</script>

<template layout="core::main">
  <ServerTable
    :bulk-actions="bulkActions"
    :inline-actions="inlineActions"
    resource-name="user"
    selectable
    :table="users"
  >
    <template #create>
      <UButton
        icon="i-heroicons-plus"
        label="Create User"
        @click="router.get(route('users.create'))"
      />
    </template>

    <template #name-cell="slotProps">
      <div v-if="slotProps?.row" class="flex items-center gap-3">
        <UAvatar
          :alt="slotProps.row.original.name"
          size="lg"
          :text="slotProps.row.original.name_initial"
        />
        <span class="font-medium text-highlighted" v-text="slotProps.row.original.name" />
      </div>
    </template>
  </ServerTable>
</template>
