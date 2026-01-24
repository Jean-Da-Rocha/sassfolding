<script setup lang="ts">
import type { NavigationMenuItem } from '@nuxt/ui';

const items: NavigationMenuItem[] = [
  {
    active: true,
    icon: 'i-lucide-house',
    label: 'Home',
  },
  {
    icon: 'i-lucide-inbox',
    label: 'Inbox',
  },
  {
    icon: 'i-lucide-users',
    label: 'Contacts',
  },
  {
    children: [
      {
        label: 'General',
      },
      {
        label: 'Members',
      },
      {
        label: 'Notifications',
      },
    ],
    defaultOpen: true,
    icon: 'i-lucide-settings',
    label: 'Settings',
  },
];
</script>

<template>
  <UApp>
    <UDashboardGroup>
      <UDashboardSidebar
        open
        :toggle="{
          color: 'primary',
          variant: 'subtle',
          class: 'rounded-full',
        }"
      >
        <template #header>
          <Logo class="h-5 w-auto" />
        </template>

        <UNavigationMenu
          :items="items"
          orientation="vertical"
        />

        <template #footer="{ collapsed }">
          <UButton
            :avatar="{
              src: 'https://github.com/benjamincanac.png',
            }"
            :block="collapsed"
            class="w-full"
            color="neutral"
            :label="collapsed ? undefined : 'Benjamin'"
            variant="ghost"
          />
        </template>
      </UDashboardSidebar>

      <UDashboardPanel>
        <UDashboardSidebarToggle />

        <main class="p-8">
          <AlertMessage />
          <slot />
        </main>
      </UDashboardPanel>
    </UDashboardGroup>
  </UApp>
</template>
