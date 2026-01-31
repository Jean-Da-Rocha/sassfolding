<script setup lang="ts">
import type { ContextMenuItem, DropdownMenuItem, TableColumn, TableRow } from '@nuxt/ui';

const props = defineProps<{ users: Table<Modules.Users.Data.UserData> }>();

const users = useTable(props, 'users');
const search = users.bindFilter<string>('search', {
  debounce: 50,
  transformUrl: { query: { page: undefined } },
});

const UButton = resolveComponent('UButton');

useHead({ title: 'Users Listing' });

const columns: TableColumn<Modules.Users.Data.UserData>[] = [
  {
    accessorKey: 'id',
    header: '#',
  },
  {
    accessorKey: 'name',
    header: ({ column }) => {
      const isSorted = column.getIsSorted();

      return h(UButton, {
        class: '-mx-2.5',
        color: 'neutral',
        icon: isSorted
          ? isSorted === 'asc'
            ? 'i-lucide-arrow-up-narrow-wide'
            : 'i-lucide-arrow-down-wide-narrow'
          : 'i-lucide-arrow-up-down',
        label: 'Name',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
        variant: 'ghost',
      });
    },
  },
  {
    accessorKey: 'email',
    header: 'Email',
    meta: { class: { td: 'w-[250px]' } },
  },
  {
    accessorKey: 'email_verified_at',
    cell: ({ row }) => {
      const date = row.getValue<string>('email_verified_at');
      return date ? new Date(date).toLocaleDateString('en-US', { dateStyle: 'medium' }) : 'Not verified';
    },
    header: 'Email Verified',
    meta: { class: { td: 'w-[180px]' } },
  },
  {
    id: 'actions',
    meta: { class: { td: 'w-[50px]' } },
  },
];

const contextItems = ref<ContextMenuItem[]>([]);

function onContextMenu(_e: Event, row: TableRow<Modules.Users.Data.UserData>) {
  contextItems.value = getItems(row.original);
}

function getActions(user: Modules.Users.Data.UserData): DropdownMenuItem[] {
  return getItems(user);
}

function getItems(user: Modules.Users.Data.UserData) {
  return [
    {
      icon: 'i-lucide-pencil',
      label: 'Edit user',
      onSelect() {
        router.navigate({ url: route('users.edit', { user: user.id }) });
      },
    },
    {
      color: 'error' as const,
      icon: 'i-lucide-trash-2',
      label: 'Delete user',
      onSelect() {
        // TODO: Implement delete confirmation dialog
        router.navigate({
          method: 'delete',
          url: route('users.destroy', { user: user.id }),
        });
      },
    },
  ];
}
</script>

<template layout="core::main">
  <div>
    <!-- Header -->
    <div class="mb-4 flex flex-col">
      <div class="w-80">
        <u-input
          v-model="search"
          autofocus
          class="w-full"
          icon="i-lucide-search"
          placeholder="Search by name or email"
        />
      </div>
    </div>

    <!-- Table -->
    <u-context-menu :items="contextItems">
      <u-table
        :columns="columns"
        :data="users.data"
        sticky
        @contextmenu="onContextMenu"
      >
        <!-- Name cell with avatar + name -->
        <template #name-cell="{ row }">
          <div class="flex items-center gap-3">
            <u-avatar
              :alt="row.original.name"
              size="lg"
              :text="row.original.nameInitial"
            />
            <div class="flex flex-col">
              <span class="font-medium text-highlighted" v-text="row.original.name" />
            </div>
          </div>
        </template>

        <!-- Actions dropdown -->
        <template #actions-cell="{ row }">
          <u-dropdown-menu :items="getActions(row.original)">
            <UButton color="neutral" icon="i-lucide-ellipsis-vertical" variant="ghost" />
          </u-dropdown-menu>
        </template>
      </u-table>
    </u-context-menu>
  </div>
</template>
