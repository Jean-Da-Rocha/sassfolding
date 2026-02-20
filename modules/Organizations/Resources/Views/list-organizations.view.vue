<script setup lang="ts">
const props = defineProps<{
  organizations: Table<Modules.Organizations.Data.OrganizationData>;
}>();

useHead({ title: 'Organizations' });
</script>

<template layout="core::main">
  <datatable
    selectable
    :table="props.organizations"
  >
    <template #create>
      <UButton
        icon="i-lucide-plus"
        label="New Organization"
        @click="router.get(route('organizations.create'))"
      />
    </template>

    <template #is_active-cell="slotProps">
      <UBadge
        v-if="slotProps?.row"
        :color="slotProps.row.original.is_active ? 'success' : 'error'"
        :label="slotProps.row.original.is_active ? 'Active' : 'Inactive'"
        variant="subtle"
      />
    </template>
  </datatable>
</template>
