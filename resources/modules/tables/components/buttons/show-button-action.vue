<script setup lang="ts">
defineProps<{ label: string; route: string }>();
</script>

<template>
  <RouterLink as="button" :href="route" class="inline-flex items-center" method="get">
    <PrimeVueSecondaryButton icon="pi" rounded size="small" type="button">
      <HeroiconsEye />
    </PrimeVueSecondaryButton>
  </RouterLink>
</template>
