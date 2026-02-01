<script setup lang="ts">
const form = useForm({
  fields: {},
  method: 'POST',
  url: route('verification.send'),
});

useHead({ title: 'Verify Email' });
</script>

<template layout="core::guest">
  <div class="mx-auto w-full max-w-lg">
    <UCard>
      <template #header>
        <div class="text-center">
          <h2 class="text-2xl font-bold tracking-tight">
            Verify your email
          </h2>
          <p
            class="
              mt-2 text-sm text-gray-600
              dark:text-gray-400
            "
          >
            We've sent you a verification link
          </p>
        </div>
      </template>

      <div class="space-y-6">
        <div
          class="
            rounded-lg bg-blue-50 p-4 text-sm text-blue-800
            dark:bg-blue-900/20 dark:text-blue-200
          "
        >
          <p>
            Thanks for signing up! Before getting started, please verify your email address by clicking on the link
            we just emailed to you.
          </p>
          <p class="mt-2">
            If you didn't receive the email, you can request a new one below.
          </p>
        </div>

        <form @submit.prevent="form.submit">
          <UButton
            block
            color="primary"
            :disabled="form.processing"
            :loading="form.processing"
            size="lg"
            type="submit"
          >
            Resend verification email
          </UButton>
        </form>
      </div>

      <template #footer>
        <div class="text-center text-sm">
          <RouterLink
            class="
              font-medium text-gray-600
              hover:text-gray-500
              dark:text-gray-400
              dark:hover:text-gray-300
            "
            :href="route('logout')"
            method="post"
          >
            Sign out
          </RouterLink>
        </div>
      </template>
    </UCard>
  </div>
</template>
