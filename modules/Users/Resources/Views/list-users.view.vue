<script setup lang="ts">
// import ServerTable from '@datatables/Components/server-table.vue';

const props = defineProps<{ users: Table<Modules.Users.Data.UserData> }>();

useHead({ title: 'Users Listing' });
</script>

<template layout="core::main">
  <ServerTable
    :actions="[
      { label: 'Edit', icon: 'i-heroicons-pencil-square', route: 'users.edit' },
      { label: 'Delete', icon: 'i-heroicons-trash', route: 'users.destroy', method: 'delete', color: 'error', confirm: true },
    ]"
    resource-name="user"
    :table="props.users"
  >
    <template #create>
      <UButton
        icon="i-heroicons-plus"
        label="Create User"
        :to="route('users.create')"
      />
    </template>

    <template #name-cell="{ row }">
      <div class="flex items-center gap-3">
        <UAvatar
          :alt="row.original.name"
          size="lg"
          :text="row.original.nameInitial"
        />
        <span class="font-medium text-highlighted" v-text="row.original.name" />
      </div>
    </template>
  </ServerTable>
</template>
