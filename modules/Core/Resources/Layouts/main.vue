<script setup lang="ts">
useToggle(useDark());

const isSidebarOpen = ref<boolean>(false);
</script>

<template>
  <div class="flex h-screen">
    <Sidebar
      class="
        fixed inset-y-0 z-30 flex transform flex-col transition-transform
        duration-300 ease-in-out
        lg:translate-x-0
      "
      :class="[isSidebarOpen ? 'translate-x-0' : '-translate-x-full']"
    />

    <div
      v-if="isSidebarOpen"
      class="
        fixed inset-0 z-20 bg-black/50 transition-opacity
        lg:hidden
      "
      @click="isSidebarOpen = false"
    />

    <div class="flex flex-1 flex-col">
      <Navbar @toggle-sidebar="isSidebarOpen = !isSidebarOpen" />

      <main
        class="
          flex-1 px-8 py-10
          sm:px-6
          lg:pl-28
        "
      >
        <AlertMessage />
        <slot />
      </main>
    </div>
  </div>
</template>
