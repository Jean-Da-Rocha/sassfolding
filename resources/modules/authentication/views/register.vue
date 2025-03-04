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

<template layout="shared::guest">
  <PrimeVueCard class="mx-auto mt-8 w-full max-w-[600px]">
    <template #content>
      <form class="space-y-4 md:space-y-6" @submit.prevent="form.submit">
        <div>
          <label
            class="mb-2 block text-sm font-medium"
            for="name"
          >
            Name
          </label>
          <PrimeVueInputText
            id="name"
            v-model="form.fields.name"
            :invalid="form.errors.hasOwnProperty('email')"
            autofocus
            class="w-full"
            type="text"
          />
          <div v-if="form.errors.name" class="mt-2 text-red-500">
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
        <div>
          <label
            class="mb-2 block text-sm font-medium"
            for="password_confirmation"
          >
            Confirm Password
          </label>
          <PrimeVuePassword
            id="password_confirmation"
            v-model="form.fields.password_confirmation"
            :feedback="false"
            :input-props="{ autocomplete: 'false' }"
            :invalid="form.errors.hasOwnProperty('password_confirmation')"
            class="w-full"
            for="password_confirmation"
            input-class="w-full"
            toggle-mask
          />
          <div v-if="form.errors.password_confirmation" class="mt-2 text-red-500">
            {{ form.errors.password_confirmation }}
          </div>
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
      <div class="text-center mt-8">
        Already have an account?
        <RouterLink :href="route('login')" class="text-primary-500 hover:text-primary-700">
          Sign In
        </RouterLink>
      </div>
    </template>
  </PrimeVueCard>
</template>
