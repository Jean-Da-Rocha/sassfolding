<template>
  <aside class="h-screen">
    <div class="flex size-full flex-col bg-surface-0 dark:bg-surface-900">
      <SidebarHeader />
      <SidebarBody>
        <MenuDropdown title="Collections">
          <Menu>
            <MenuItem link="test">
              Cards
            </MenuItem>
          </Menu>
          <Menu>
            <MenuItem link="test">
              Sets
            </MenuItem>
          </Menu>
        </MenuDropdown>
      </SidebarBody>
      <SidebarFooter />
    </div>
  </aside>
</template>
