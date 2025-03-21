<script setup lang="ts">
const darkMode = useDark();
useToggle(darkMode);
</script>

<template>
  <footer class="mt-auto">
    <div class="text-center">
      <PrimeVueToggleSwitch v-model="darkMode">
        <template #handle="{ checked }">
          <i class="!text-xs pi" :class="[{ 'pi-moon': checked, 'pi-sun': !checked }]" />
        </template>
      </PrimeVueToggleSwitch>
    </div>
  </footer>
</template>
