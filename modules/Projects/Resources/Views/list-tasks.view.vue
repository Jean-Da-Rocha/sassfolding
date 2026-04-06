<script setup lang="ts">
const props = defineProps<{
  priorityColors: Record<string, string>;
  priorityLabels: Record<string, string>;
  statusColors: Record<string, string>;
  statusLabels: Record<string, string>;
  tasks: Table<Modules.Projects.Data.TaskData>;
}>();

useHead({ title: 'Tasks' });
</script>

<template layout="core::main">
  <datatable
    :hidden-columns="['estimated_hours']"
    selectable
    :table="props.tasks"
  >
    <template #create>
      <UButton
        icon="i-lucide-plus"
        label="New Task"
        @click="router.get(route('tasks.create'))"
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

    <template #priority-cell="slotProps">
      <UBadge
        v-if="slotProps?.row"
        :color="props.priorityColors[slotProps.row.original.priority] ?? 'neutral'"
        :label="props.priorityLabels[slotProps.row.original.priority] ?? slotProps.row.original.priority"
        variant="subtle"
      />
    </template>
  </datatable>
</template>
