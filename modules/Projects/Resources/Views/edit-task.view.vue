<script setup lang="ts">
import type { DateValue } from '@internationalized/date';
import { CalendarDate } from '@internationalized/date';

const props = defineProps<{
  priorities: Record<string, string>;
  projects: Record<string, string>;
  statuses: Record<string, string>;
  task: Modules.Projects.Data.TaskData;
}>();

const { close } = useDialog();

const form = useForm<{
  description: string;
  due_at: string;
  estimated_hours: number | null;
  priority: string;
  project_id: string;
  status: string;
  title: string;
}>({
  fields: {
    description: props.task.description ?? '',
    due_at: props.task.due_at ?? '',
    estimated_hours: props.task.estimated_hours !== null ? Number(props.task.estimated_hours) : null,
    priority: props.task.priority,
    project_id: String(props.task.project_id),
    status: props.task.status,
    title: props.task.title,
  },
  hooks: {
    success: () => close(),
  },
  method: 'PUT',
  url: route('tasks.update', { task: props.task.id }),
});

const inputDate = useTemplateRef('inputDate');

const dueAtDate = computed<DateValue | undefined>({
  get: () => {
    if (!form.fields.due_at) {
      return undefined;
    }
    const [year, month, day] = form.fields.due_at.split('-').map(Number);
    return new CalendarDate(year, month, day);
  },
  set: (value) => {
    form.fields.due_at = value
      ? `${value.year}-${String(value.month).padStart(2, '0')}-${String(value.day).padStart(2, '0')}`
      : '';
  },
});

const projectItems = computed(() =>
  Object.entries(props.projects).map(([value, label]) => ({
    label,
    value,
  })),
);

const statusItems = computed(() =>
  Object.entries(props.statuses).map(([value, label]) => ({
    label,
    value,
  })),
);

const priorityItems = computed(() =>
  Object.entries(props.priorities).map(([value, label]) => ({
    label,
    value,
  })),
);

useHead({ title: 'Edit Task' });
</script>

<template>
  <HybridlyModal description="Modify task information" title="Edit Task">
    <template #default="{ close: closeModal }">
      <UCard>
        <template #header>
          <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold">
              Edit Task
            </h2>
            <UButton
              color="neutral"
              icon="i-lucide-x"
              variant="ghost"
              @click="closeModal"
            />
          </div>
        </template>

        <form class="space-y-4" @submit.prevent="form.submit()">
          <UFormField :error="form.errors.title" label="Title">
            <UInput
              v-model="form.fields.title"
              class="w-full"
              placeholder="Task title"
              type="text"
            />
          </UFormField>

          <UFormField :error="form.errors.description" label="Description">
            <UTextarea
              v-model="form.fields.description"
              class="w-full"
              placeholder="Optional description"
            />
          </UFormField>

          <UFormField :error="form.errors.project_id" label="Project">
            <USelectMenu
              v-model="form.fields.project_id"
              class="w-full"
              :items="projectItems"
              placeholder="Select project"
              value-key="value"
            />
          </UFormField>

          <div class="grid grid-cols-2 gap-4">
            <UFormField :error="form.errors.status" label="Status">
              <USelectMenu
                v-model="form.fields.status"
                class="w-full"
                :items="statusItems"
                value-key="value"
              />
            </UFormField>

            <UFormField :error="form.errors.priority" label="Priority">
              <USelectMenu
                v-model="form.fields.priority"
                class="w-full"
                :items="priorityItems"
                value-key="value"
              />
            </UFormField>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <UFormField :error="form.errors.due_at" label="Due Date">
              <UInputDate
                ref="inputDate"
                v-model="dueAtDate"
                class="w-full"
              >
                <template #trailing>
                  <UPopover>
                    <UButton
                      color="neutral"
                      icon="i-lucide-calendar"
                      size="xs"
                      variant="ghost"
                    />
                    <template #content>
                      <UCalendar v-model="dueAtDate" class="p-2" />
                    </template>
                  </UPopover>
                </template>
              </UInputDate>
            </UFormField>

            <UFormField :error="form.errors.estimated_hours" label="Estimated Hours">
              <UInputNumber
                v-model="form.fields.estimated_hours"
                class="w-full"
                :min="0"
                placeholder="0.0"
                :step="0.5"
              />
            </UFormField>
          </div>
        </form>

        <template #footer>
          <div class="flex justify-end gap-2">
            <UButton
              color="neutral"
              label="Cancel"
              variant="outline"
              @click="closeModal"
            />
            <UButton
              label="Save Changes"
              :loading="form.processing"
              @click="form.submit()"
            />
          </div>
        </template>
      </UCard>
    </template>
  </HybridlyModal>
</template>
