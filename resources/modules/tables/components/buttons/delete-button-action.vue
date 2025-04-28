<script setup lang="ts">
defineProps<{ label: string }>();
</script>

<template>
  <PrimeVueDangerButton rounded size="small" type="button" icon="pi">
    <HeroiconsTrash />
  </PrimeVueDangerButton>
</template>
