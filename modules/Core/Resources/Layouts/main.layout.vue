<script setup lang="ts">
useFlashToast();

const appName = useProperty('app.name');
const isOpen = ref(false);

const { footerItems, mainItems, pageTitle } = useNavigation();
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
            :items="mainItems"
            orientation="vertical"
            popover
            tooltip
          />

          <UNavigationMenu
            v-if="footerItems.length"
            class="mt-auto"
            :collapsed="collapsed"
            :items="footerItems"
            orientation="vertical"
            tooltip
          />
        </template>

        <template #footer="{ collapsed }">
          <UserDropdownMenu :collapsed="Boolean(collapsed)" />
        </template>
      </UDashboardSidebar>

      <UDashboardPanel>
        <UDashboardNavbar :title="pageTitle">
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
