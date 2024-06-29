<script setup lang="ts">
function useFlashMessages() {
  const { flash } = useProperties();

  return flash?.messages.map(flashMessage => ({
    key: generateUniqueIdentifier(),
    message: flashMessage.message,
    severity: flashMessage.severity,
  })) || [];
}

function useValidationErrors() {
  const { errors } = useProperties();

  // Check if the errors object has a custom Laravel validation bag (i.e 'updatePassword').
  const hasCustomValidationBag = Object.values(errors).every(error => typeof error === 'object' && error !== null);

  const errorValues = hasCustomValidationBag
    ? Object.values(errors).flatMap(errorObject => Object.values(errorObject))
    : Object.values(errors);

  return errorValues.map(error => ({
    error,
    key: generateUniqueIdentifier(),
  }));
}

const flashMessages = computed(useFlashMessages);
const validationErrors = computed(useValidationErrors);

function generateUniqueIdentifier() {
  return Math.floor(Date.now() * Math.random());
}
</script>

<template>
  <div :key="key" v-for="{ message, key, severity } in flashMessages">
    <PrimeVueMessage :life="5000" :severity="severity" :sticky="false" v-if="message">
      {{ message }}
    </PrimeVueMessage>
  </div>

  <PrimeVueMessage :key="key" :life="5000" :sticky="false" severity="error" v-for="{ error, key } in validationErrors">
    {{ error }}
  </PrimeVueMessage>
</template>
