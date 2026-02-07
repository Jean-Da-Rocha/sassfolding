<script setup lang="ts">
type UserData = Modules.Users.Data.UserData;

defineProps<{ users: Table<UserData> }>();

useHead({ title: 'Users Listing' });

const inlineActions = [
  {
    icon: 'i-lucide-square-pen',
    label: 'Edit',
    route: 'users.edit',
  },
  {
    color: 'error',
    confirm: true,
    icon: 'i-lucide-trash-2',
    label: 'Delete',
    method: 'delete',
    route: 'users.destroy',
  },
] satisfies InlineAction<UserData>[];

const bulkActions = [
  {
    color: 'error',
    icon: 'i-lucide-trash-2',
    label: 'Delete Selected',
    onSelect: () => {},
  },
  {
    icon: 'i-lucide-mail',
    label: 'Send Email',
    onSelect: () => {},
  },
  {
    icon: 'i-lucide-download',
    label: 'Export CSV',
    onSelect: () => {},
  },
  {
    color: 'warning',
    icon: 'i-lucide-ban',
    label: 'Deactivate',
    onSelect: () => {},
  },
] satisfies BulkAction<UserData>[];
</script>

<template layout="core::main">
  <Datatable
    :bulk-actions="bulkActions"
    :inline-actions="inlineActions"
    resource-name="user"
    selectable
    :table="users"
  >
    <template #create>
      <UButton
        icon="i-lucide-plus"
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
  </Datatable>
</template>
