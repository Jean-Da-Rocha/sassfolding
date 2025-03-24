<script setup lang="ts">
defineProps<{ label: string; route: string }>();
</script>

<template>
  <RouterLink
    as="button"
    :href="route"
    class="lg:inline-flex lg:items-center w-full"
    method="get"
  >
    <PrimeVueButton
      icon="pi pi-plus"
      :label="label"
      size="small"
      class="w-full lg:w-auto"
      type="button"
    />
  </RouterLink>
</template>
