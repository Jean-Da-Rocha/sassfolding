<script setup lang="ts">
const flashStatus = useProperty('flash.status');

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
  <PrimeVueCard class="mx-auto mt-8 w-full max-w-[600px]">
    <template #content>
      <PrimeVueMessage :life="5000" :sticky="false" class="-mt-3" severity="success" v-if="flashStatus?.message">
        {{ flashStatus.message }}
      </PrimeVueMessage>
      <div class="mb-4 text-sm">
        Forgot your password? No problem. Just let us know your email address and we will email you a password reset
        link that will allow you to choose a new one.
      </div>
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
          <PrimeVueButton
            :disabled="form.processing"
            class="w-full"
            label="Email Password Reset Link"
            type="submit"
          />
        </div>
      </form>
    </template>
  </PrimeVueCard>
</template>
