<script setup lang="ts">
import type Popover from 'primevue/popover';

const emit = defineEmits<{
  (event: 'toggleSidebar'): void;
}>();

const { user } = useSecurity();

const profileOverlay = useTemplateRef<InstanceType<typeof Popover>>('profileOverlay');

const toggleProfileOverlay = (event: Event) => profileOverlay.value?.toggle(event);
</script>

<template>
  <nav
    class="top-0 z-10 flex items-center gap-x-6 bg-surface-0 p-4 dark:bg-surface-900 px-4 sm:px-6 lg:px-8 border-b border-surface-300 dark:border-surface-700"
  >
    <button class="-m-2.5 p-2.5 lg:hidden" type="button" @click="emit('toggleSidebar')">
      <i class="pi pi-bars size-6 text-surface-700 dark:text-surface-0 hover:cursor-pointer" />
    </button>
    <div class="flex items-center gap-6 ml-auto">
      <i class="pi pi-inbox !text-xl text-surface-700 dark:text-surface-0 hover:cursor-pointer hover:text-primary" />
      <i class="pi pi-bell !text-xl text-surface-700 dark:text-surface-0 hover:cursor-pointer hover:text-primary" />
      <span
        class="h-8 w-8 bg-primary rounded-full text-surface-0 flex items-center justify-center hover:cursor-pointer"
        @click="toggleProfileOverlay"
      >
        {{ user?.name_initial }}
      </span>
    </div>

    <PrimeVuePopover ref="profileOverlay">
      <div class="w-32">
        <RouterLink
          :href="route('profile')"
          class="flex items-center p-3 text-xs hover:bg-surface-100 dark:text-surface-0 dark:hover:bg-surface-700 rounded-md"
          method="get"
          @click="toggleProfileOverlay"
        >
          <i class="pi pi-user" />
          <span class="mx-2">Profile</span>
        </RouterLink>
        <RouterLink
          :href="route('logout')"
          class="flex items-center p-3 text-xs hover:bg-surface-100 dark:text-surface-0 dark:hover:bg-surface-700 rounded-md"
          method="post"
        >
          <i class="pi pi-sign-out" />
          <span class="mx-2">Logout</span>
        </RouterLink>
      </div>
    </PrimeVuePopover>
  </nav>
</template>
