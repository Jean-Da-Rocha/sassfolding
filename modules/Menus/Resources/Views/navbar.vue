<script setup lang="ts">
const emit = defineEmits<{
  (event: 'toggleSidebar'): void;
}>();

const { user } = useSecurity();

const profileOverlay = useTemplateRef<InstanceType<typeof Popover>>('profileOverlay');

const toggleProfileOverlay = (event: Event) => profileOverlay.value?.toggle(event);
</script>

<template>
  <nav
    class="
      border-surface-300 bg-surface-0 top-0 z-10 flex items-center gap-x-6
      border-b p-4 px-4
      dark:border-surface-700 dark:bg-surface-900
      sm:px-6
      lg:px-8
    "
  >
    <button
      class="
        -m-2.5 p-2.5
        lg:hidden
      "
      type="button"
      @click="emit('toggleSidebar')"
    >
      <HeroiconsBars3
        class="
          text-surface-700 size-6
          dark:text-surface-0
          hover:cursor-pointer
        "
      />
    </button>
    <div class="ml-auto flex items-center gap-6">
      <HeroiconsInbox
        class="
          text-surface-700 size-6
          hover:text-primary hover:cursor-pointer
          dark:text-surface-0
        "
      />
      <HeroiconsBell
        class="
          text-surface-700 size-6
          hover:text-primary hover:cursor-pointer
          dark:text-surface-0
        "
      />
      <span
        class="
          bg-primary text-surface-0 flex h-8 w-8 items-center justify-center
          rounded-full
          dark:text-surface-700
          hover:cursor-pointer
        "
        @click="toggleProfileOverlay"
      >
        {{ user?.name_initial }}
      </span>
    </div>

    <PrimeVuePopover ref="profileOverlay">
      <div class="w-32">
        <RouterLink
          class="
            hover:bg-surface-100
            dark:text-surface-0 dark:hover:bg-surface-700
            flex items-center rounded-md p-3 text-xs
          "
          :href="route('profile')"
          method="get"
          @click="toggleProfileOverlay"
        >
          <HeroiconsUser class="size-5" />
          <span class="mx-2">Profile</span>
        </RouterLink>
        <RouterLink
          class="
            hover:bg-surface-100
            dark:text-surface-0 dark:hover:bg-surface-700
            flex items-center rounded-md p-3 text-xs
          "
          :href="route('logout')"
          method="post"
        >
          <HeroiconsArrowLeftOnRectangle class="size-5" />
          <span class="mx-2">Logout</span>
        </RouterLink>
      </div>
    </PrimeVuePopover>
  </nav>
</template>
