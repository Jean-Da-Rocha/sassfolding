<script setup lang="ts">
useFlashToast();
</script>

<template>
  <UApp>
    <div class="flex h-screen items-center justify-center p-4">
      <slot />
    </div>
  </UApp>
</template>
