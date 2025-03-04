<script setup lang="ts">
const { user } = useSecurity();

const { initials } = useInitials(user.value?.name);

const profileOverlayForDesktop = ref();

const toggle = (event: Event) => profileOverlayForDesktop.value.toggle(event);
</script>

<template>
  <footer class="mt-auto" @click="toggle">
    <hr class="border-top-1 surface-border mx-3 mb-3">
    <div
      v-ripple
      class="flex items-center gap-x-4 px-6 py-3 text-sm font-semibold text-surface-700 hover:cursor-pointer hover:bg-surface-50 dark:bg-surface-900 dark:text-surface-0/80 dark:hover:bg-surface-700"
    >
      <span
        class="flex size-8 items-center justify-center rounded-full border bg-surface-50 text-[0.625rem] font-medium dark:bg-surface-900 dark:text-surface-0/80"
      >
        {{ initials }}
      </span>
      <span>{{ user?.name }}</span>
    </div>

    <PrimeVuePopover ref="profileOverlayForDesktop" class="ml-5 w-full max-w-[200px]">
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
  </footer>
</template>
