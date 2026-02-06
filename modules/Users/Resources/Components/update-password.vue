<script setup lang="ts">
const form = useForm<{
  current_password: string;
  password: string;
  password_confirmation: string;
}>({
  fields: {
    current_password: '',
    password: '',
    password_confirmation: '',
  },
  method: 'PUT',
  url: route('user-password.update'),
});

const showCurrentPassword = ref(false);
const showPassword = ref(false);
const showPasswordConfirmation = ref(false);
</script>

<template layout="core::main">
  <FormSection>
    <template #title>
      Update Password
    </template>

    <template #description>
      Ensure your account is using a long, random password to stay secure.
    </template>

    <template #form>
      <UCard>
        <form class="space-y-6" @submit.prevent="form.submit">
          <div class="space-y-2">
            <label class="block text-sm font-medium" for="current_password">
              Current password
            </label>
            <UInput
              id="current_password"
              v-model="form.fields.current_password"
              autocomplete="current-password"
              class="w-full"
              :invalid="Boolean(form.errors.current_password)"
              placeholder="Enter your current password"
              size="lg"
              :type="showCurrentPassword ? 'text' : 'password'"
            >
              <template #trailing>
                <UButton
                  :aria-label="showCurrentPassword ? 'Hide password' : 'Show password'"
                  color="neutral"
                  :icon="showCurrentPassword ? 'i-heroicons-eye-slash' : 'i-heroicons-eye'"
                  size="sm"
                  variant="link"
                  @click="showCurrentPassword = !showCurrentPassword"
                />
              </template>
            </UInput>
            <p
              v-if="form.errors.current_password"
              class="
                text-sm text-red-600
                dark:text-red-400
              "
            >
              {{ form.errors.current_password }}
            </p>
          </div>

          <div class="space-y-2">
            <label class="block text-sm font-medium" for="password">
              New password
            </label>
            <UInput
              id="password"
              v-model="form.fields.password"
              autocomplete="new-password"
              class="w-full"
              :invalid="Boolean(form.errors.password)"
              placeholder="Create a strong password"
              size="lg"
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
            <p
              v-if="form.errors.password"
              class="
                text-sm text-red-600
                dark:text-red-400
              "
            >
              {{ form.errors.password }}
            </p>
          </div>

          <div class="space-y-2">
            <label class="block text-sm font-medium" for="password_confirmation">
              Confirm password
            </label>
            <UInput
              id="password_confirmation"
              v-model="form.fields.password_confirmation"
              autocomplete="new-password"
              class="w-full"
              :invalid="Boolean(form.errors.password_confirmation)"
              placeholder="Re-enter your password"
              size="lg"
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
            <p
              v-if="form.errors.password_confirmation"
              class="
                text-sm text-red-600
                dark:text-red-400
              "
            >
              {{ form.errors.password_confirmation }}
            </p>
          </div>

          <div class="flex justify-end">
            <UButton
              color="primary"
              :disabled="form.processing"
              label="Submit"
              :loading="form.processing"
              size="lg"
              type="submit"
            />
          </div>
        </form>
      </UCard>
    </template>
  </FormSection>
</template>
