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

useHead({ title: 'Sign Up' });
</script>

<template layout="shared::main">
  <PrimeVueCard class="mx-auto mt-8 w-full max-w-[600px]">
    <template #content>
      <form @submit.prevent="form.submit" class="space-y-4 md:space-y-6">
        <div>
          <label
            class="mb-2 block text-sm font-medium"
            for="name"
          >
            Name
          </label>
          <PrimeVueInputText
            :invalid="form.errors.hasOwnProperty('email')"
            autofocus
            class="w-full"
            id="name"
            type="text"
            v-model="form.fields.name"
          />
          <div class="mt-2 text-red-500" v-if="form.errors.name">
            {{ form.errors.name }}
          </div>
        </div>
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
        <div>
          <label
            class="mb-2 block text-sm font-medium"
            for="password_confirmation"
          >
            Confirm Password
          </label>
          <PrimeVuePassword
            :feedback="false"
            :input-props="{ autocomplete: false }"
            :invalid="form.errors.hasOwnProperty('password_confirmation')"
            class="w-full"
            for="password_confirmation"
            id="password_confirmation"
            input-class="w-full"
            toggle-mask
            v-model="form.fields.password_confirmation"
          />
          <div class="mt-2 text-red-500" v-if="form.errors.password_confirmation">
            {{ form.errors.password_confirmation }}
          </div>
        </div>
        <div class="flex items-center justify-between">
          <RouterLink
            :href="route('login')"
            class="text-sm font-medium text-primary-500 hover:text-primary-300 hover:underline"
          >
            Already registered?
          </RouterLink>
        </div>
        <div>
          <PrimeVueButton
            :disabled="form.processing"
            class="w-full"
            label="Register"
            type="submit"
          />
        </div>
      </form>
    </template>
  </PrimeVueCard>
</template>
