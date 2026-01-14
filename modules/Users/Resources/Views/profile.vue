<script setup lang="ts">
const { user } = useAuth();

useHead({ title: 'User Profile' });
</script>

<template layout="core::main">
  <div class="grid gap-6">
    <ProfileInfo :user="user" />
    <UpdatePassword />
  </div>
</template>
