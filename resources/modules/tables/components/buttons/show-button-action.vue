<script setup lang="ts">
import Eye from '@primevue/icons/eye';

defineProps<{ label: string; route: string }>();
</script>

<template>
  <RouterLink as="button" :href="route" class="inline-flex items-center" method="get">
    <PrimeVueSecondaryButton
      icon="pi"
      rounded
      size="small"
      type="button"
    >
      <Eye />
    </PrimeVueSecondaryButton>
  </RouterLink>
</template>
