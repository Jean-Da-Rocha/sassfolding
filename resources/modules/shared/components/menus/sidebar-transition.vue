<script setup lang="ts">
const sidebarIsOpen = defineModel<boolean>('sidebarIsOpen');
</script>

<template>
  <HeadlessTransitionRoot :show="sidebarIsOpen" as="template">
    <HeadlessDialog @close="sidebarIsOpen = false" as="div" class="relative z-50 lg:hidden">
      <HeadlessTransitionChild
        as="template"
        enter="transition-opacity ease-linear duration-300"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="transition-opacity ease-linear duration-300"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-gray-900/80" />
      </HeadlessTransitionChild>

      <div class="fixed inset-0 flex">
        <HeadlessTransitionChild
          as="template"
          enter="transition ease-in-out duration-300 transform"
          enter-from="-translate-x-full"
          enter-to="translate-x-0"
          leave="transition ease-in-out duration-300 transform"
          leave-from="translate-x-0"
          leave-to="-translate-x-full"
        >
          <HeadlessDialogPanel class="relative mr-16 flex w-full max-w-xs flex-1">
            <HeadlessTransitionChild
              as="template"
              enter="ease-in-out duration-300"
              enter-from="opacity-0"
              enter-to="opacity-100"
              leave="ease-in-out duration-300"
              leave-from="opacity-100"
              leave-to="opacity-0"
            >
              <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                <button @click="sidebarIsOpen = false" class="-m-2.5 p-2.5" type="button">
                  <span class="sr-only">Close sidebar</span>
                  <HeroiconsXMark aria-hidden="true" class="size-6 text-surface-700 dark:text-surface-0/80" />
                </button>
              </div>
            </HeadlessTransitionChild>
            <slot />
          </HeadlessDialogPanel>
        </HeadlessTransitionChild>
      </div>
    </HeadlessDialog>
  </HeadlessTransitionRoot>
</template>
