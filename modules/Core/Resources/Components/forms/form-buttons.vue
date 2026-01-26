<script setup lang="ts">
defineProps<{ cancelLabel?: string; cancelUrl: string; isFormProcessing: boolean; submitLabel?: string }>();
</script>

<template>
  <div
    class="
      flex flex-col gap-3
      md:flex-row md:justify-end
    "
  >
    <RouterLink
      class="
        w-full
        md:w-auto
      "
      :href="cancelUrl"
      method="get"
    >
      <UButton
        block
        color="neutral"
        :label="cancelLabel ?? 'Cancel'"
        size="lg"
        type="button"
        variant="outline"
      />
    </RouterLink>
    <UButton
      block
      class="
        w-full
        md:w-auto
      "
      color="primary"
      :disabled="isFormProcessing"
      :label="submitLabel ?? 'Submit'"
      :loading="isFormProcessing"
      size="lg"
      type="submit"
    />
  </div>
</template>
