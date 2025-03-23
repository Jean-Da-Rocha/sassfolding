<script setup lang="ts">
defineProps<{ label: string }>();
</script>

<template>
  <PrimeVueButton
    v-tooltip.top="label"
    icon="pi pi-trash"
    rounded
    severity="danger"
    size="small"
    type="button"
  />
</template>
