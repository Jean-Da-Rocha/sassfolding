<script setup lang="ts">
export type FlashMessageReturnType = Pick<App.Data.FlashMessageData, 'message' | 'severity'> & {
  key: number;
};

export type ValidationErrorReturnType = {
  error: string | string[];
  key: number;
};

function useFlashMessages(): FlashMessageReturnType[] {
  const { flash } = useProperties();

  return flash?.messages.map((flashMessage: App.Data.FlashMessageData) => ({
    key: crypto.randomUUID(),
    message: flashMessage.message,
    severity: flashMessage.severity,
  })) || [];
}

function useValidationErrors(): ValidationErrorReturnType[] {
  const { errors } = useProperties();

  return Object.values(errors).map((error: string[]) => ({
    error,
    key: crypto.randomUUID(),
  }));
}

const flashMessages = computed<FlashMessageReturnType[]>(useFlashMessages);
const validationErrors = computed<ValidationErrorReturnType[]>(useValidationErrors);
</script>

<template>
  <div v-for="{ message, key, severity } in flashMessages" :key="key">
    <PrimeVueMessage v-if="message" :life="5000" :severity="severity" :sticky="false" class="mb-5">
      {{ message }}
    </PrimeVueMessage>
  </div>

  <PrimeVueMessage
    v-for="{ error, key } in validationErrors" :key="key"
    :life="5000"
    :sticky="false"
    class="mb-5"
    severity="error"
  >
    {{ error }}
  </PrimeVueMessage>
</template>
