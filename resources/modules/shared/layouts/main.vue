<script setup lang="ts">
const darkMode = useDark();

useToggle(darkMode);
</script>

<template>
  <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:flex-col">
    <Sidebar />
  </div>

  <!-- Navbar visible only for mobile devices -->
  <!--  <Navbar /> -->

  <main class="py-10 lg:pl-24">
    <div class="px-4 sm:px-6 lg:px-8">
      <AlertMessage />
      <slot />
    </div>
  </main>
</template>
