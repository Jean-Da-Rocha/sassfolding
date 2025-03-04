<script setup lang="ts">
import type Popover from 'primevue/popover';
import { useTemplateRef } from 'vue';

const { user } = useSecurity();

const profileOverlay = useTemplateRef<InstanceType<typeof Popover>>('profileOverlay');

const toggleProfileOverlay = (event: Event) => profileOverlay.value?.toggle(event);
</script>

<template>
  <footer class="mt-auto">
    <div>
      <hr class="border-surface-300 dark:border-surface-700 mx-3 mb-3">
      <div
        class="m-4 flex cursor-pointer items-center justify-center gap-2 rounded-sm p-2 text-sm text-surface-700 transition-colors duration-150 hover:bg-surface-100 dark:text-surface-0 dark:hover:bg-surface-800"
        @click="toggleProfileOverlay"
      >
        <span
          class="flex size-8 items-center justify-center rounded-full bg-surface-200 text-[0.625rem] font-medium text-surface-700 dark:bg-surface-900 dark:text-surface-0/80"
        >
          {{ getInitials(user?.name) }}
        </span>
        <span class="mr-8">{{ user?.name }}</span>
      </div>
    </div>

    <PrimeVuePopover ref="profileOverlay" class="ml-5 w-full max-w-[200px]">
      <div class="w-full">
        <RouterLink
          :href="route('profile')"
          as="button"
          class="flex w-full items-center p-2 text-sm text-surface-700 hover:bg-surface-100 dark:text-surface-0/80 dark:hover:bg-surface-700"
          method="get"
          @click="toggleProfileOverlay"
        >
          <HeroiconsUser class="size-5" />
          <span class="mx-2 text-sm font-medium">Profile</span>
        </RouterLink>
        <RouterLink
          :href="route('logout')"
          as="button"
          class="flex w-full items-center p-2 text-sm text-surface-700 hover:bg-surface-100 dark:text-surface-0/80 dark:hover:bg-surface-700"
          method="post"
        >
          <HeroiconsArrowLeftOnRectangle class="size-5" />
          <span class="mx-2 text-sm font-medium">Logout</span>
        </RouterLink>
      </div>
    </PrimeVuePopover>
  </footer>
</template>
