<script setup lang="ts">
const { user } = useSecurity();

useHead({ title: 'User Profile' });
</script>

<template layout="shared::main">
  <div class="grid gap-6">
    <ToggleDarkMode />
    <ProfileInfo :user="user" />
    <UpdatePassword />
  </div>
</template>
