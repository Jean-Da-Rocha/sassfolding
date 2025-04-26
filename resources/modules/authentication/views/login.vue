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

useHead({ title: 'Sign In' });
</script>

<template layout="shared::guest">
  <PrimeVueCard class="mx-auto w-full max-w-[600px]">
    <template #content>
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
            <label class="mb-2 block text-sm font-medium" for="password">
              Password
            </label>
            <PrimeVuePassword
              id="password"
              v-model="form.fields.password"
              :feedback="false"
              :invalid="form.errors.hasOwnProperty('password')"
              for="password"
              toggle-mask
            />
            <div v-if="form.errors.password" class="mt-2 text-red-500">
              {{ form.errors.password }}
            </div>
          </div>
          <div class="flex items-center justify-between">
            <div class="flex items-start">
              <div class="flex h-5 items-center">
                <PrimeVueCheckbox id="remember" v-model="form.fields.remember" :binary="true" />
              </div>
              <div class="ml-3 text-sm">
                <label for="remember">Remember me</label>
              </div>
            </div>
            <RouterLink
              :href="route('password.request')"
              class="text-sm font-medium text-primary-500 hover:text-primary-700 hover:underline"
            >
              Forgot your password?
            </RouterLink>
          </div>
          <div>
            <PrimeVueButton :disabled="form.processing" label="Sign In" type="submit" />
          </div>
        </form>
      </PrimeVueFluid>
      <div class="text-center">
        Don't have an account?
        <RouterLink :href="route('register')" class="text-primary-500 hover:text-primary-700">
          Sign Up
        </RouterLink>
      </div>
    </template>
  </PrimeVueCard>
</template>
