<script setup lang="ts">
defineProps<{ label: string }>();
</script>

<template>
  <PrimeVueDangerButton icon="pi" rounded size="small" type="button">
    <HeroiconsTrash />
  </PrimeVueDangerButton>
</template>
