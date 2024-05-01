<script setup lang="ts">
const { user } = useSecurity();

useHead({ title: 'User Profile' });
</script>

<template layout="shared::main">
  <ProfileInfo :user="user" />
  <UpdatePassword />
</template>
