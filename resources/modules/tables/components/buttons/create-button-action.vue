<script setup lang="ts">
defineProps<{ label: string; route: string }>();
</script>

<template>
  <RouterLink as="button" class="lg:inline-flex lg:items-center w-full" :href="route" method="get">
    <PrimeVuePrimaryButton class="w-full lg:w-auto" size="small" type="button">
      <HeroiconsPlus /> {{ label }}
    </PrimeVuePrimaryButton>
  </RouterLink>
</template>
