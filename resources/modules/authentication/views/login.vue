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

<template layout="shared::main">
  <PrimeVueCard class="mx-auto mt-8 w-full max-w-[600px]">
    <template #content>
      <form @submit.prevent="form.submit" class="space-y-4 md:space-y-6">
        <div>
          <label
            class="mb-2 block text-sm font-medium"
            for="email"
          >
            Email
          </label>
          <PrimeVueInputText
            :invalid="form.errors.hasOwnProperty('email')"
            autofocus
            class="w-full"
            id="email"
            type="text"
            v-model="form.fields.email"
          />
          <div class="mt-2 text-red-500" v-if="form.errors.email">
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
            :feedback="false"
            :input-props="{ autocomplete: false }"
            :invalid="form.errors.hasOwnProperty('password')"
            class="w-full"
            for="password"
            id="password"
            input-class="w-full"
            toggle-mask
            v-model="form.fields.password"
          />
          <div class="mt-2 text-red-500" v-if="form.errors.password">
            {{ form.errors.password }}
          </div>
        </div>
        <div class="flex items-center justify-between">
          <div class="flex items-start">
            <div class="flex h-5 items-center">
              <PrimeVueCheckbox :binary="true" id="remember" v-model="form.fields.remember" />
            </div>
            <div class="ml-3 text-sm">
              <label for="remember">Remember me</label>
            </div>
          </div>
          <RouterLink :href="route('password.request')" class="text-sm font-medium text-primary-500 hover:text-primary-300 hover:underline">
            Forgot your password?
          </RouterLink>
        </div>
        <div>
          <PrimeVueButton
            :disabled="form.processing"
            class="w-full"
            label="Log in"
            type="submit"
          />
        </div>
      </form>
    </template>
  </PrimeVueCard>
</template>
