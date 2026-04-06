<script setup lang="ts">
const props = defineProps<{
  organizations: Record<string, string>;
  statuses: Record<string, string>;
}>();

const { close } = useDialog();

const form = useForm<{
  description: string;
  name: string;
  organization_id: string;
  status: string;
}>({
  fields: {
    description: '',
    name: '',
    organization_id: '',
    status: 'active',
  },
  hooks: {
    success: () => close(),
  },
  method: 'POST',
  url: route('projects.store'),
});

const organizationItems = computed(() =>
  Object.entries(props.organizations).map(([value, label]) => ({
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

useHead({ title: 'Create Project' });
</script>

<template>
  <HybridlyModal description="Create a new project" title="Create Project">
    <template #default="{ close: closeModal }">
      <UCard>
        <template #header>
          <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold">
              Create Project
            </h2>
            <UButton
              color="neutral"
              icon="i-lucide-x"
              variant="ghost"
              @click="closeModal"
            />
          </div>
        </template>

        <form class="space-y-4" @submit.prevent="form.submit">
          <UFormField :error="form.errors.name" label="Name">
            <UInput
              v-model="form.fields.name"
              class="w-full"
              placeholder="Project name"
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

          <UFormField :error="form.errors.organization_id" label="Organization">
            <USelectMenu
              v-model="form.fields.organization_id"
              class="w-full"
              :items="organizationItems"
              placeholder="Select organization"
              value-key="value"
            />
          </UFormField>

          <UFormField :error="form.errors.status" label="Status">
            <USelectMenu
              v-model="form.fields.status"
              class="w-full"
              :items="statusItems"
              value-key="value"
            />
          </UFormField>
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
              label="Create Project"
              :loading="form.processing"
              @click="form.submit"
            />
          </div>
        </template>
      </UCard>
    </template>
  </HybridlyModal>
</template>
