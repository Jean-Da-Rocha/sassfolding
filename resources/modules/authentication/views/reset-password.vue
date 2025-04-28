<script setup lang="ts">
const props = defineProps<{
  email: string;
  token: string;
}>();

const form = useForm<{ email: string; password: string; password_confirmation: string; token: string }>({
  fields: {
    email: props.email,
    password: '',
    password_confirmation: '',
    token: props.token,
  },
  method: 'POST',
  url: route('password.update'),
});

useHead({ title: 'Reset Password' });
</script>

<template layout="shared::guest">
  <PrimeVueCard class="mx-auto mt-8 w-full max-w-[600px]">
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
          <div>
            <label class="mb-2 block text-sm font-medium" for="password_confirmation">
              Confirm Password
            </label>
            <PrimeVuePassword
              id="password_confirmation"
              v-model="form.fields.password_confirmation"
              :feedback="false"
              :invalid="form.errors.hasOwnProperty('password_confirmation')"
              for="password_confirmation"
              toggle-mask
            />
            <div v-if="form.errors.password_confirmation" class="mt-2 text-red-500">
              {{ form.errors.password_confirmation }}
            </div>
          </div>
          <div>
            <PrimeVuePrimaryButton :disabled="form.processing" label="Reset Password" type="submit" />
          </div>
        </form>
      </PrimeVueFluid>
    </template>
  </PrimeVueCard>
</template>
