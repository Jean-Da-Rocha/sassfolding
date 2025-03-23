<script setup lang="ts">
const props = defineProps<{
  users: Table<App.Data.UserData>;
}>();

useHead({ title: 'Users Listing' });
</script>

<template layout="shared::main">
  <Datatable :table="props.users">
    <template #create-button>
      <CreateButtonAction label="Create User" :route="route('users.create')" />
    </template>
    <template #edit-button="{ recordId }">
      <EditButtonAction label="Edit this user" :route="route('users.edit', { user: recordId })" />
    </template>
    <template #show-button="{ recordId }">
      <ShowButtonAction label="View user details" :route="route('users.show', { user: recordId })" />
    </template>
    <template #delete-button="{ confirmDestructiveAction, recordId }">
      <DeleteButtonAction
        label="Delete this user"
        @click="confirmDestructiveAction(route('users.destroy', { user: recordId }))"
      />
    </template>
  </Datatable>
</template>
