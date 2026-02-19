<script setup lang="ts">
type UserData = Modules.Users.Data.UserData;

defineProps<{ users: Table<UserData> }>();

useHead({ title: 'Users Listing' });
</script>

<template layout="core::main">
  <Datatable
    :hidden-columns="['updated_at']"
    selectable
    :table="users"
  >
    <template #create>
      <UButton
        icon="i-lucide-plus"
        @click="router.get(route('users.create'))"
      />
    </template>

    <template #name-cell="slotProps">
      <div v-if="slotProps?.row" class="flex items-center gap-3">
        <UAvatar
          :alt="slotProps.row.original.name"
          size="lg"
          :text="slotProps.row.original.name_initial"
        />
        <span class="font-medium text-highlighted" v-text="slotProps.row.original.name" />
      </div>
    </template>
  </Datatable>
</template>
