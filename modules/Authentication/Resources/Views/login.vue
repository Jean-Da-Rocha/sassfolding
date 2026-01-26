<script setup lang="ts">
const form = useForm<{ email: string; password: string; remember: boolean }>({
  fields: {
    email: '',
    password: '',
    remember: false,
  },
  method: 'POST',
  url: route('login'),
});

const showPassword = ref<boolean>(false);

useHead({ title: 'Sign In' });
</script>

<template layout="core::guest">
  <div class="mx-auto w-full max-w-lg">
    <UCard>
      <template #header>
        <div class="text-center">
          <h2 class="text-2xl font-bold tracking-tight">
            Welcome back
          </h2>
          <p
            class="
              mt-2 text-sm text-gray-600
              dark:text-gray-400
            "
          >
            Sign in to your account to continue
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
            autocomplete="current-password"
            class="w-full"
            :invalid="Boolean(form.errors.password)"
            placeholder="Enter your password"
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

        <div class="flex items-center justify-between">
          <label class="flex cursor-pointer items-center gap-2">
            <UCheckbox id="remember" v-model="form.fields.remember" />
            <span class="text-sm">Remember me</span>
          </label>

          <RouterLink
            class="
              text-sm font-medium text-primary-600
              hover:text-primary-500
              dark:text-primary-400
              dark:hover:text-primary-300
            "
            :href="route('password.request')"
          >
            Forgot password?
          </RouterLink>
        </div>

        <UButton
          block
          color="primary"
          :disabled="form.processing"
          :loading="form.processing"
          size="lg"
          type="submit"
        >
          Sign in
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
            Don't have an account?
          </span>
          <RouterLink
            class="
              font-medium text-primary-600
              hover:text-primary-500
              dark:text-primary-400
              dark:hover:text-primary-300
            "
            :href="route('register')"
          >
            Sign up
          </RouterLink>
        </div>
      </template>
    </UCard>
  </div>
</template>
