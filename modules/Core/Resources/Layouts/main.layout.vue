<script setup lang="ts">
useFlashToast();

const { current } = useRoute();

const appName = useProperty('app.name');

const isOpen = ref<boolean>(false);

const links = computed(() => [
  [
    {
      icon: 'heroicons-home',
      label: 'Home',
    },
    {
      badge: '4',
      icon: 'heroicons-inbox',
      label: 'Inbox',
    },
    {
      active: current.value === 'users.index',
      icon: 'heroicons-users',
      label: 'Users',
      onClick: () => router.get(route('users.index')),
    },
    {
      icon: 'heroicons-cog-6-tooth',
      label: 'Settings',
    },
  ],
] satisfies NavigationMenuItem[][]);
</script>

<template>
  <UApp>
    <UDashboardGroup>
      <UDashboardSidebar
        id="default"
        class="bg-elevated/25"
        collapsible
        :open="isOpen"
        resizable
        :ui="{ footer: 'lg:border-t lg:border-default' }"
      >
        <template #header="{ collapsed }">
          <div v-if="!collapsed" class="flex items-center">
            <Logo />
            <span class="text-primary capitalize">{{ appName }}</span>
          </div>
          <Logo v-else />
        </template>
        <template #default="{ collapsed }">
          <UNavigationMenu
            :collapsed="collapsed"
            :items="links[0]"
            orientation="vertical"
            popover
            tooltip
          />

          <UNavigationMenu
            class="mt-auto"
            :collapsed="collapsed"
            :items="links[1]"
            orientation="vertical"
            tooltip
          />
        </template>

        <template #footer="{ collapsed }">
          <UserDropdownMenu :collapsed="Boolean(collapsed)" />
        </template>
      </UDashboardSidebar>

      <UDashboardPanel>
        <UDashboardNavbar title="Users">
          <template #leading>
            <UDashboardSidebarCollapse />
          </template>
        </UDashboardNavbar>
        <main class="flex-1 overflow-auto p-8">
          <slot />
        </main>
      </UDashboardPanel>
    </UDashboardGroup>
  </UApp>
</template>
