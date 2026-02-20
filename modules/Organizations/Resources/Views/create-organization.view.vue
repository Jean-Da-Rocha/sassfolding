<script setup lang="ts">
const { close } = useDialog();

const form = useForm<{
  name: string;
  slug: string;
}>({
  fields: {
    name: '',
    slug: '',
  },
  hooks: {
    success: () => close(),
  },
  method: 'POST',
  url: route('organizations.store'),
});

useHead({ title: 'Create Organization' });
</script>

<template>
  <HybridlyModal description="Create a new organization" title="Create Organization">
    <template #default="{ close: closeModal }">
      <UCard>
        <template #header>
          <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold">
              Create Organization
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
              label="Create Organization"
              :loading="form.processing"
              @click="form.submit"
            />
          </div>
        </template>
      </UCard>
    </template>
  </HybridlyModal>
</template>
