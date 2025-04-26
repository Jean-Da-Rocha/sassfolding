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

<template layout="shared::guest">
  <PrimeVueCard class="mx-auto w-full max-w-[600px]">
    <template #content>
      <AlertMessage />
      <div class="mb-4 text-sm">
        Forgot your password? No problem. Just let us know your email address and we will email you a password reset
        link that will allow you to choose a new one.
      </div>
      <PrimeVueFluid>
        <form v-focustrap class="space-y-4 md:space-y-6" @submit.prevent="form.submit">
          <div>
            <label class="mb-2 block text-sm font-medium" for="email">
              Email
            </label>
            <PrimeVueInputText
              id="email"
              v-model="form.fields.email"
              :invalid="form.errors.hasOwnProperty('email')"
              :autofocus="true"
              type="text"
            />
            <div v-if="form.errors.email" class="mt-2 text-red-500">
              {{ form.errors.email }}
            </div>
          </div>
          <div>
            <PrimeVueButton :disabled="form.processing" label="Email Password Reset Link" type="submit" />
          </div>
        </form>
      </PrimeVueFluid>
      <div class="text-center">
        <span>Or, return to the</span>
        <RouterLink class="text-primary-500 hover:text-primary-700" :href="route('login')">
          login page
        </RouterLink>
      </div>
    </template>
  </PrimeVueCard>
</template>
