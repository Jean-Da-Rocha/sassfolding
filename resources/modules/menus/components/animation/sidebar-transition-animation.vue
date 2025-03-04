<script setup lang="ts">
const isSidebarForMobileDeviceOpened = defineModel<boolean>('isSidebarForMobileDeviceOpened');
</script>

<template>
  <HeadlessTransitionRoot :show="isSidebarForMobileDeviceOpened" as="template">
    <HeadlessDialog as="div" class="relative z-50 lg:hidden" @close="isSidebarForMobileDeviceOpened = false">
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
          class="opacity-0"
          enter="ease-in-out duration-500"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="ease-in-out duration-500"
          leave-from="opacity-100"
          leave-to="opacity-0"
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
                <button class="-m-2.5 p-2.5" type="button" @click="isSidebarForMobileDeviceOpened = false">
                  <span class="sr-only">Close sidebar</span>
                  <HeroiconsXMark aria-hidden="true" class="size-6 text-white dark:text-surface-0/80 hover:cursor-pointer" />
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
