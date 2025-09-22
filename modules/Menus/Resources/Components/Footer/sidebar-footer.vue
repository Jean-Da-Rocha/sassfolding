<script setup lang="ts">
const darkMode = useDark();
useToggle(darkMode);
</script>

<template>
  <footer class="mt-auto">
    <div class="text-center">
      <PrimeVueToggleSwitch v-model="darkMode">
        <template #handle="{ checked }">
          <HeroiconsMoon v-if="checked" />
          <HeroiconsSun v-else />
        </template>
      </PrimeVueToggleSwitch>
    </div>
  </footer>
</template>
