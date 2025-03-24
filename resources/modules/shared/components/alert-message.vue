<script setup lang="ts">
export type FlashMessageReturnType = Pick<App.Data.FlashMessageData, 'message' | 'severity'> & {
  key: `${string}-${string}-${string}-${string}-${string}`;
};

function useFlashMessages(): FlashMessageReturnType[] {
  const { flash } = useProperties();

  return flash?.messages.map((flashMessage: App.Data.FlashMessageData) => ({
    key: crypto.randomUUID(),
    message: flashMessage.message,
    severity: flashMessage.severity,
  })) || [];
}

const flashMessages = computed<FlashMessageReturnType[]>(useFlashMessages);
</script>

<template>
  <div v-for="{ message, key, severity } in flashMessages" :key="key">
    <PrimeVueMessage v-if="message" :life="5000" :severity="severity" :sticky="false" class="mb-5">
      {{ message }}
    </PrimeVueMessage>
  </div>
</template>
