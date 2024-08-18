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
            :input-props="{ autocomplete: 'false' }"
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
            :input-props="{ autocomplete: 'false' }"
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
        <div>
          <PrimeVueButton
            :disabled="form.processing"
            class="w-full"
            label="Reset Password"
            type="submit"
          />
        </div>
      </form>
    </template>
  </PrimeVueCard>
</template>
