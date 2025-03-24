<script setup lang="ts">
defineProps<{ label: string; route: string }>();
</script>

<template>
  <RouterLink
    as="button"
    :href="route"
    class="inline-flex items-center"
    method="get"
  >
    <PrimeVueButton
      v-tooltip.top="label"
      icon="pi pi-pencil"
      severity="info"
      rounded
      size="small"
      type="button"
    />
  </RouterLink>
</template>
