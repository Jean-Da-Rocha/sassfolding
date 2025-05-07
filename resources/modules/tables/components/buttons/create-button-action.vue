<script setup lang="ts">
defineProps<{ label: string; route: string }>();
</script>

<template>
  <RouterLink
    as="button"
    class="
      w-full
      lg:inline-flex lg:items-center
    "
    :href="route"
    method="get"
  >
    <PrimeVuePrimaryButton
      class="
        w-full
        lg:w-auto
      "
      size="small"
      type="button"
    >
      <HeroiconsPlus />
      {{ label }}
    </PrimeVuePrimaryButton>
  </RouterLink>
</template>
