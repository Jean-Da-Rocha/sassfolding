<template>
  <aside>
    <div class="flex size-full flex-col bg-surface-0 dark:bg-surface-900">
      <div class="flex shrink-0 items-center justify-between px-4 pt-4">
        <span class="inline-flex items-center gap-2">
          <img alt="" src="https://img.freepik.com/vecteurs-libre/vecteur-degrade-logo-colore-oiseau_343694-1365.jpg?size=338&ext=jpg&ga=GA1.1.2008272138.1723852800&semt=ais_hybrid" width="128">
        </span>
      </div>
      <div class="mt-4 overflow-y-auto">
        <MenuDropdown title="Collection">
          <Menu>
            <MenuItem link="test">
              My collection
            </MenuItem>
          </Menu>
        </MenuDropdown>
      </div>
    </div>
  </aside>
</template>
