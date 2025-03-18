<template>
  <nav
    class="sticky top-0 z-40 flex items-center gap-x-6 bg-surface-50 p-4 shadow-xs dark:bg-surface-900 sm:px-6"
  >
    <button class="-m-2.5 p-2.5 lg:hidden" type="button">
      <span class="sr-only">Open sidebar</span>
      <HeroiconsBars3 aria-hidden="true" class="size-6 text-surface-700 dark:text-surface-0 hover:cursor-pointer" />
    </button>
    <div class="flex-1 text-sm font-semibold leading-6 text-surface-700 dark:text-surface-0">
      Dashboard
    </div>
  </nav>
</template>
