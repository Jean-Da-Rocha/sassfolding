<script setup lang="ts">
const props = defineProps<{
  organization: Modules.Organizations.Data.OrganizationData;
}>();

const { close } = useDialog();

const form = useForm<{
  name: string;
  slug: string;
}>({
  fields: {
    name: props.organization.name,
    slug: props.organization.slug,
  },
  hooks: {
    success: () => close(),
  },
  method: 'PUT',
  url: route('organizations.update', { organization: props.organization.id }),
});

useHead({ title: 'Edit Organization' });
</script>

<template>
  <HybridlyModal description="Modify organization information" title="Edit Organization">
    <template #default="{ close: closeModal }">
      <UCard>
        <template #header>
          <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold">
              Edit Organization
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
          <UFormField :error="form.errors.name" label="Name">
            <UInput
              v-model="form.fields.name"
              class="w-full"
              placeholder="Acme Corp"
              type="text"
            />
          </UFormField>

          <UFormField :error="form.errors.slug" label="Slug">
            <UInput
              v-model="form.fields.slug"
              class="w-full"
              placeholder="acme-corp"
              type="text"
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
