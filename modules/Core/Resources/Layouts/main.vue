<script setup lang="ts">
import type { NavigationMenuItem } from '@nuxt/ui';

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
      children: [
        {
          active: current.value === 'users.create',
          label: 'Create',
          onClick: () => router.get(route('users.create')),
        },
        {
          active: current.value === 'users.index',
          label: 'List',
          onClick: () => router.get(route('users.index')),
        },
      ],
      defaultOpen: true,
      icon: 'heroicons-users',
      label: 'Users',
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
        <main class="p-8">
          <AlertMessage />
          <slot />
        </main>
      </UDashboardPanel>
    </UDashboardGroup>
  </UApp>
</template>
