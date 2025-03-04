<script setup lang="ts">
const sidebarIsOpen = defineModel<boolean>('sidebarIsOpen');

const { user } = useSecurity();

const { initials } = useInitials(user.value?.name);

const profileOverlayForMobile = ref();

const toggle = (event: Event) => profileOverlayForMobile.value.toggle(event);
</script>

<template>
  <nav
    class="sticky top-0 z-40 flex items-center gap-x-6 bg-surface-50 p-4 shadow-sm dark:bg-surface-900 sm:px-6 lg:hidden"
  >
    <button class="-m-2.5 p-2.5 lg:hidden" type="button" @click="sidebarIsOpen = true">
      <span class="sr-only">Open sidebar</span>
      <HeroiconsBars3 aria-hidden="true" class="size-6 text-surface-700 dark:text-surface-0/80" />
    </button>
    <div class="flex-1 text-sm font-semibold leading-6 text-surface-700 dark:text-surface-0/80">
      Dashboard
    </div>
    <span
      class="hover:cursor-pointer"
      @click="(event: Event) => profileOverlayForMobile.toggle(event)"
    >
      <span class="sr-only">Your profile</span>
      <span
        class="flex size-8 items-center justify-center rounded-full border bg-surface-50 text-[0.625rem] font-medium dark:bg-surface-900 dark:text-surface-0/80"
      >
        {{ initials }}
      </span>
    </span>
  </nav>

  <PrimeVuePopover ref="profileOverlayForMobile">
    <div class="w-full">
      <RouterLink
        :href="route('profile')"
        as="button"
        class="flex w-full items-center p-2 text-sm text-surface-700 hover:bg-surface-100 dark:text-surface-0/80 dark:hover:bg-surface-700"
        method="get"
        @click="toggle"
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
</template>
