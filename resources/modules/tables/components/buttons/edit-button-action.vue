<script setup lang="ts">
defineProps<{ label: string; route: string }>();
</script>

<template>
  <RouterLink as="button" class="inline-flex items-center" :href="route" method="get">
    <PrimeVueContrastButton icon="pi" rounded size="small" type="button">
      <HeroiconsPencil />
    </PrimeVueContrastButton>
  </RouterLink>
</template>
