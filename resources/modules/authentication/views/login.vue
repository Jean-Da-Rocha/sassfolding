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
  <PrimeVueCard class="mx-auto mt-8 w-full max-w-[600px]">
    <template #content>
      <form class="space-y-4 md:space-y-6" @submit.prevent="form.submit">
        <div>
          <label
            class="mb-2 block text-sm font-medium"
            for="email"
          >
            Email
          </label>
          <PrimeVueInputText
            id="email"
            v-model="form.fields.email"
            :invalid="form.errors.hasOwnProperty('email')"
            autofocus
            class="w-full"
            type="text"
          />
          <div v-if="form.errors.email" class="mt-2 text-red-500">
            {{ form.errors.email }}
          </div>
        </div>
        <div>
          <label
            class="mb-2 block text-sm font-medium"
            for="password"
          >
            Password
          </label>
          <PrimeVuePassword
            id="password"
            v-model="form.fields.password"
            :feedback="false"
            :input-props="{ autocomplete: 'false' }"
            :invalid="form.errors.hasOwnProperty('password')"
            class="w-full"
            for="password"
            input-class="w-full"
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
          <RouterLink :href="route('password.request')" class="text-sm font-medium text-primary-500 hover:text-primary-700 hover:underline">
            Forgot your password?
          </RouterLink>
        </div>
        <div>
          <PrimeVueButton
            :disabled="form.processing"
            class="w-full"
            label="Sign In"
            type="submit"
          />
        </div>
      </form>
      <div class="text-center mt-8">
        Don't have an account?
        <RouterLink :href="route('register')" class="text-primary-500 hover:text-primary-700">
          Sign Up
        </RouterLink>
      </div>
    </template>
  </PrimeVueCard>
</template>
