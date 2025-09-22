<template>
  <SidebarLayout>
    <SidebarHeader />
    <SidebarBody>
      <Menu>
        <MenuItem
          v-for="menuItem in navigation"
          :key="menuItem.navigationLinkName"
          :menu-item="menuItem"
        />
      </Menu>
    </SidebarBody>
    <SidebarFooter />
  </SidebarLayout>
</template>
