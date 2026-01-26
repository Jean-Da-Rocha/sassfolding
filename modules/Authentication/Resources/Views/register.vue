<script setup lang="ts">
const form = useForm<{ email: string; name: string; password: string; password_confirmation: string }>({
  fields: {
    email: '',
    name: '',
    password: '',
    password_confirmation: '',
  },
  method: 'POST',
  reset: true,
  url: route('register'),
});

const showPassword = ref<boolean>(false);
const showPasswordConfirmation = ref<boolean>(false);

useHead({ title: 'Sign Up' });
</script>

<template layout="core::guest">
  <div class="mx-auto w-full max-w-lg">
    <UCard>
      <template #header>
        <div class="text-center">
          <h2 class="text-2xl font-bold tracking-tight">
            Create an account
          </h2>
          <p
            class="
              mt-2 text-sm text-gray-600
              dark:text-gray-400
            "
          >
            Get started by filling in the information below
          </p>
        </div>
      </template>

      <form class="space-y-6" @submit.prevent="form.submit">
        <div class="space-y-2">
          <label class="block text-sm font-medium" for="name">
            Full name
          </label>
          <UInput
            id="name"
            v-model="form.fields.name"
            autocomplete="name"
            class="w-full"
            :invalid="Boolean(form.errors.name)"
            placeholder="John Doe"
            size="lg"
            type="text"
          />
          <p
            v-if="form.errors.name"
            class="
              text-sm text-red-600
              dark:text-red-400
            "
          >
            {{ form.errors.name }}
          </p>
        </div>

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
            placeholder="you@example.com"
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
            Password
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

        <UButton
          block
          color="primary"
          :disabled="form.processing"
          :loading="form.processing"
          size="lg"
          type="submit"
        >
          Create account
        </UButton>
      </form>

      <template #footer>
        <div class="text-center text-sm">
          <span
            class="
              text-gray-600
              dark:text-gray-400
            "
          >
            Already have an account?
          </span>
          <RouterLink
            class="
              font-medium text-primary-600
              hover:text-primary-500
              dark:text-primary-400
              dark:hover:text-primary-300
            "
            :href="route('login')"
          >
            Sign in
          </RouterLink>
        </div>
      </template>
    </UCard>
  </div>
</template>
