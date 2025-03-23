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
      icon="pi pi-plus"
      :label="label"
      size="small"
      type="button"
    />
  </RouterLink>
</template>
