<script setup lang="ts">
defineProps<{ cancelLabel?: string; cancelUrl: string; isFormProcessing: boolean; submitLabel?: string }>();
</script>

<template>
  <div class="md:text-right mt-6">
    <RouterLink :href="cancelUrl" method="get">
      <PrimeVueContrastButton
        class="w-full md:mr-3 md:w-auto"
        :label="cancelLabel ?? 'Cancel'"
        size="small"
        type="button"
      />
    </RouterLink>
    <PrimeVuePrimaryButton
      class="w-full mt-3 md:w-auto"
      :disabled="isFormProcessing"
      :label="submitLabel ?? 'Submit'"
      size="small"
      type="submit"
    />
  </div>
</template>
