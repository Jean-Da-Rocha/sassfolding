<template>
  <SidebarTransition>
    <!-- Responsive sidebar for mobile devices -->
    <Sidebar />
  </SidebarTransition>

  <!-- Static sidebar for desktop devices -->
  <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
    <Sidebar />
  </div>

  <main class="py-10 lg:pl-72">
    <div class="px-4 sm:px-6 lg:px-8">
      <AlertMessage />
      <slot />
    </div>
  </main>
</template>
