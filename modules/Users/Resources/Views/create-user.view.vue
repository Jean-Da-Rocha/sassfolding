<script setup lang="ts">
const { close } = useDialog();

const form = useForm<{
  email: string;
  name: string;
  password: string;
  password_confirmation: string;
}>({
  fields: {
    email: '',
    name: '',
    password: '',
    password_confirmation: '',
  },
  hooks: {
    success: () => close(),
  },
  method: 'POST',
  url: route('users.store'),
});

const showPassword = ref<boolean>(false);
const showPasswordConfirmation = ref<boolean>(false);

useHead({ title: 'Create User' });
</script>

<template>
  <HybridlyModal description="Add a new user to your application" title="Create User">
    <template #default="{ close: closeModal }">
      <UCard>
        <template #header>
          <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold">
              Create User
            </h2>
            <UButton
              color="neutral"
              icon="i-heroicons-x-mark"
              variant="ghost"
              @click="closeModal"
            />
          </div>
        </template>

        <form class="space-y-4" @submit.prevent="form.submit">
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

          <UFormField :error="form.errors.password" label="Password">
            <UInput
              v-model="form.fields.password"
              autocomplete="new-password"
              class="w-full"
              placeholder="Create a strong password"
              :type="showPassword ? 'text' : 'password'"
            >
              <template #trailing>
                <UButton
                  :aria-label="showPassword ? 'Hide password' : 'Show password'"
                  color="neutral"
                  :icon="showPassword ? 'i-heroicons-eye-slash' : 'i-heroicons-eye'"
                  size="sm"
                  variant="link"
                  @click="showPassword = !showPassword"
                />
              </template>
            </UInput>
          </UFormField>

          <UFormField :error="form.errors.password_confirmation" label="Confirm password">
            <UInput
              v-model="form.fields.password_confirmation"
              autocomplete="new-password"
              class="w-full"
              placeholder="Re-enter your password"
              :type="showPasswordConfirmation ? 'text' : 'password'"
            >
              <template #trailing>
                <UButton
                  :aria-label="showPasswordConfirmation ? 'Hide password' : 'Show password'"
                  color="neutral"
                  :icon="showPasswordConfirmation ? 'i-heroicons-eye-slash' : 'i-heroicons-eye'"
                  size="sm"
                  variant="link"
                  @click="showPasswordConfirmation = !showPasswordConfirmation"
                />
              </template>
            </UInput>
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
              label="Create User"
              :loading="form.processing"
              @click="form.submit"
            />
          </div>
        </template>
      </UCard>
    </template>
  </HybridlyModal>
</template>
