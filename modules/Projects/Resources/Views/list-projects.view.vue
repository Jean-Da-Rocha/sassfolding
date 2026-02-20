<script setup lang="ts">
const props = defineProps<{
  projects: Table<Modules.Projects.Data.ProjectData>;
  statusColors: Record<string, string>;
  statusLabels: Record<string, string>;
}>();

useHead({ title: 'Projects' });
</script>

<template layout="core::main">
  <datatable
    selectable
    :table="props.projects"
  >
    <template #create>
      <UButton
        icon="i-lucide-plus"
        label="New Project"
        @click="router.get(route('projects.create'))"
      />
    </template>

    <template #status-cell="slotProps">
      <UBadge
        v-if="slotProps?.row"
        :color="props.statusColors[slotProps.row.original.status] ?? 'neutral'"
        :label="props.statusLabels[slotProps.row.original.status] ?? slotProps.row.original.status"
        variant="subtle"
      />
    </template>
  </datatable>
</template>
