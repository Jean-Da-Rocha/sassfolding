<script setup lang="ts">
const props = defineProps<{
  users: Table<App.Data.UserData>;
}>();

useHead({ title: 'Users Listing' });
</script>

<template layout="shared::main">
  <Datatable :table="props.users" />
</template>
