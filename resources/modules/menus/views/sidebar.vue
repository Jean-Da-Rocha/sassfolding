<template>
  <SidebarLayout>
    <SidebarHeader>
      <span class="font-bold uppercase">Sassfolding</span>
    </SidebarHeader>
    <SidebarBody>
      <MenuDropdown
        v-for="(menuItems, menuDropDownTitle) in navigation"
        :key="menuDropDownTitle"
        :title="menuDropDownTitle"
      >
        <Menu>
          <MenuItem
            v-for="menuItem in menuItems"
            :key="menuItem.navigationLinkName"
            :menu-item="menuItem"
          >
            {{ menuItem.navigationLinkName }}
          </MenuItem>
        </Menu>
      </MenuDropdown>
    </SidebarBody>
    <SidebarFooter />
  </SidebarLayout>
</template>
