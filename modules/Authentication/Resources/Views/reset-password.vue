<script setup lang="ts">
const props = defineProps<{
  email: string;
  token: string;
}>();

const form = useForm<{ email: string; password: string; password_confirmation: string; token: string }>({
  fields: {
    email: props.email,
    password: '',
    password_confirmation: '',
    token: props.token,
  },
  method: 'POST',
  url: route('password.update'),
});

const showPassword = ref<boolean>(false);
const showPasswordConfirmation = ref<boolean>(false);

useHead({ title: 'Reset Password' });
</script>

<template layout="core::guest">
  <div class="mx-auto w-full max-w-lg">
    <UCard>
      <template #header>
        <div class="text-center">
          <h2 class="text-2xl font-bold tracking-tight">
            Reset your password
          </h2>
          <p
            class="
              mt-2 text-sm text-gray-600
              dark:text-gray-400
            "
          >
            Enter your new password below
          </p>
        </div>
      </template>

      <form class="space-y-6" @submit.prevent="form.submit">
        <div class="space-y-2">
          <label class="block text-sm font-medium" for="email">
            Email address
          </label>
          <UInput
            id="email"
            v-model="form.fields.email"
            autocomplete="email"
            class="w-full"
            :invalid="Boolean(form.errors.email)"
            readonly
            size="lg"
            type="email"
          />
          <p
            v-if="form.errors.email"
            class="
              text-sm text-red-600
              dark:text-red-400
            "
          >
            {{ form.errors.email }}
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
            Confirm new password
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

        <UButton
          block
          color="primary"
          :disabled="form.processing"
          :loading="form.processing"
          size="lg"
          type="submit"
        >
          Reset password
        </UButton>
      </form>
    </UCard>
  </div>
</template>
