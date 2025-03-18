<template>
  <nav
    class="top-0 z-40 flex items-center gap-x-6 bg-surface-0 p-4 dark:bg-surface-900 px-4 sm:px-6 lg:px-8 border-b border-surface-300 dark:border-surface-700 "
  >
    <button class="-m-2.5 p-2.5 lg:hidden" type="button">
      <i class="pi pi-bars size-6 text-surface-700 dark:text-surface-0 hover:cursor-pointer" />
    </button>
    <div class="flex gap-6 ml-auto">
      <i class="pi pi-inbox !text-2xl text-surface-700 dark:text-surface-0 hover:cursor-pointer" />
      <i class="pi pi-bell !text-2xl text-surface-700 dark:text-surface-0 hover:cursor-pointer" />
      <PrimeVueAvatar label="S" shape="circle" />
    </div>
  </nav>
</template>
