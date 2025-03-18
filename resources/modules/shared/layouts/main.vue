<script setup lang="ts">
const darkMode = useDark();
useToggle(darkMode);
</script>

<template>
  <div class="flex">
    <Sidebar class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:flex-col" />

    <div class="flex flex-1 flex-col">
      <Navbar />

      <main class="flex-1 py-10 sm:px-6 px-8 lg:pl-24">
        <AlertMessage />
        <slot />
      </main>
    </div>
  </div>
</template>
