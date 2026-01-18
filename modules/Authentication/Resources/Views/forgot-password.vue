<script setup lang="ts">
const form = useForm<{ email: string }>({
  fields: {
    email: '',
  },
  method: 'POST',
  url: route('password.email'),
});

useHead({ title: 'Forgot Password' });
</script>

<template layout="core::guest">
  <div class="mx-auto w-full max-w-lg">
    <UCard>
      <template #header>
        <div class="text-center">
          <h2 class="text-2xl font-bold tracking-tight">
            Forgot password?
          </h2>
          <p
            class="
              mt-2 text-sm text-gray-600
              dark:text-gray-400
            "
          >
            No problem. Enter your email and we'll send you a reset link.
          </p>
        </div>
      </template>

      <div class="space-y-6">
        <AlertMessage />

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
              v-if="form.errors.email" class="
                text-sm text-red-600
                dark:text-red-400
              "
            >
              {{ form.errors.email }}
            </p>
          </div>

          <UButton
            class="w-full"
            color="primary"
            :disabled="form.processing"
            :loading="form.processing"
            size="lg"
            type="submit"
          >
            Send reset link
          </UButton>
        </form>
      </div>

      <template #footer>
        <div class="text-center text-sm">
          <span
            class="
              text-gray-600
              dark:text-gray-400
            "
          >Remember your password?</span>
          {{ ' ' }}
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
