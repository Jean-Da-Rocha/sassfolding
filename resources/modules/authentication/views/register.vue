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
  <PrimeVueCard class="mx-auto w-full max-w-[600px]">
    <template #content>
      <PrimeVueFluid>
        <form v-focustrap class="space-y-4 md:space-y-6" @submit.prevent="form.submit">
          <div>
            <label class="mb-2 block text-sm font-medium" for="name">
              Name
            </label>
            <PrimeVueInputText
              id="name"
              v-model="form.fields.name"
              :autofocus="true"
              :invalid="form.errors.hasOwnProperty('email')"
              type="text"
            />
            <div v-if="form.errors.name" class="mt-2 text-red-500">
              {{ form.errors.name }}
            </div>
          </div>
          <div>
            <label class="mb-2 block text-sm font-medium" for="email">
              Email
            </label>
            <PrimeVueInputText
              id="email"
              v-model="form.fields.email"
              :autofocus="true"
              :invalid="form.errors.hasOwnProperty('email')"
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
              for="password"
              :invalid="form.errors.hasOwnProperty('password')"
              toggle-mask
            />
            <div v-if="form.errors.password" class="mt-2 text-red-500">
              {{ form.errors.password }}
            </div>
          </div>
          <div>
            <label class="mb-2 block text-sm font-medium" for="password_confirmation">
              Confirm Password
            </label>
            <PrimeVuePassword
              id="password_confirmation"
              v-model="form.fields.password_confirmation"
              :feedback="false"
              for="password_confirmation"
              :invalid="form.errors.hasOwnProperty('password_confirmation')"
              toggle-mask
            />
            <div v-if="form.errors.password_confirmation" class="mt-2 text-red-500">
              {{ form.errors.password_confirmation }}
            </div>
          </div>
          <div>
            <PrimeVuePrimaryButton :disabled="form.processing" label="Register" type="submit" />
          </div>
        </form>
      </PrimeVueFluid>
      <div class="text-center">
        Already have an account?
        <RouterLink class="text-primary-500 hover:text-primary-700" :href="route('login')">
          Sign In
        </RouterLink>
      </div>
    </template>
  </PrimeVueCard>
</template>
