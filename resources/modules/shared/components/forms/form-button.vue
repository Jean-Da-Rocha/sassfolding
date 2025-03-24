<script setup lang="ts">
defineProps<{ cancelRoute?: string }>();
</script>

<template>
  <div class="text-right">
    <RouterLink
      v-if="cancelRoute"
      as="button"
      :href="cancelRoute"
      class="inline-flex items-center"
    >
      <PrimeVueButton label="Cancel" size="small" type="button" severity="contrast" class="mr-3" />
    </RouterLink>
    <PrimeVueButton label="Submit" size="small" type="submit" />
  </div>
</template>
