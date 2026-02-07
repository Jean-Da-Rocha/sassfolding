<script setup lang="ts">
const props = defineProps<{
  user: Modules.Users.Data.UserData;
}>();

const { close } = useDialog();

const form = useForm<{
  email: string;
  name: string;
}>({
  fields: {
    email: props.user.email,
    name: props.user.name,
  },
  hooks: {
    success: () => close(),
  },
  method: 'PUT',
  url: route('users.update', { user: props.user.id }),
});

useHead({ title: 'Edit User' });
</script>

<template>
  <HybridlyModal description="Modify user information" title="Edit User">
    <template #default="{ close: closeModal }">
      <UCard>
        <template #header>
          <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold">
              Edit User
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
          <UFormField :error="form.errors.email" label="Email address">
            <UInput
              v-model="form.fields.email"
              autocomplete="email"
              class="w-full"
              placeholder="user@example.com"
              type="email"
            />
          </UFormField>

          <UFormField :error="form.errors.name" label="Full name">
            <UInput
              v-model="form.fields.name"
              autocomplete="name"
              class="w-full"
              placeholder="John Doe"
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
