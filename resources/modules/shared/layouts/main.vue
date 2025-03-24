<script setup lang="ts">
useToggle(useDark());

const isSidebarOpen = ref<boolean>(false);
</script>

<template>
  <div class="flex h-screen">
    <Sidebar
      class="lg:translate-x-0 fixed inset-y-0 z-30 flex flex-col transform transition-transform duration-300 ease-in-out"
      :class="[isSidebarOpen ? 'translate-x-0' : '-translate-x-full']"
    />

    <div
      v-if="isSidebarOpen"
      class="fixed inset-0 bg-black/50 z-20 lg:hidden transition-opacity"
      @click="isSidebarOpen = false"
    />

    <div class="flex flex-1 flex-col">
      <Navbar @toggle-sidebar="isSidebarOpen = !isSidebarOpen" />

      <main class="flex-1 py-10 sm:px-6 px-8 lg:pl-28">
        <AlertMessage />
        <slot />
      </main>
    </div>
  </div>
</template>
