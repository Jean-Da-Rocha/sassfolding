<script setup lang="ts">
</script>

<template>
  <aside class="h-screen w-full">
    <div class="flex size-full flex-col bg-surface-0 dark:bg-surface-900">
      <SidebarHeader />
      <SidebarBody>
        <MenuDropdown title="Menu Dropdown 1">
          <Menu>
            <MenuItem link="test">
              Menu Item 1
            </MenuItem>
          </Menu>
          <Menu>
            <MenuItem link="test">
              Menu Item 2
            </MenuItem>
          </Menu>
        </MenuDropdown>
        <MenuDropdown title="Menu Dropdown 1">
          <Menu>
            <MenuItem link="test">
              Menu Item 1
            </MenuItem>
          </Menu>
          <Menu>
            <MenuItem link="test">
              Menu Item 2
            </MenuItem>
          </Menu>
        </MenuDropdown>
        <MenuDropdown title="Menu Dropdown 1">
          <Menu>
            <MenuItem link="test">
              Menu Item 1
            </MenuItem>
          </Menu>
          <Menu>
            <MenuItem link="test">
              Menu Item 2
            </MenuItem>
          </Menu>
        </MenuDropdown>
      </SidebarBody>
      <SidebarFooter />
    </div>
  </aside>
</template>
