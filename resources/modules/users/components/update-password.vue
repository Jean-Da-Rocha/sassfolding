<script setup lang="ts">
const form = useForm<{ current_password: string; password: string; password_confirmation: string }>({
  fields: {
    current_password: '',
    password: '',
    password_confirmation: '',
  },
  method: 'PUT',
  url: route('user-password.update'),
});
</script>

<template layout="shared::main">
  <FormSection>
    <template #title>
      Update Password
    </template>

    <template #description>
      Ensure your account is using a long, random password to stay secure.
    </template>

    <template #form>
      <PrimeVueCard>
        <template #content>
          <form class="space-y-4 md:space-y-6" @submit.prevent="form.submit">
            <div>
              <label class="mb-2 block text-sm font-medium dark:text-surface-0" for="current_password">
                Current password
              </label>
              <PrimeVuePassword
                id="current_password"
                v-model="form.fields.current_password"
                :feedback="false"
                :invalid="form.errors.hasOwnProperty('password')"
                for="current_password"
                toggle-mask
                fluid
                size="small"
              />
              <div v-if="form.errors.current_password" class="mt-2 text-red-500">
                {{ form.errors.current_password }}
              </div>
            </div>
            <div>
              <label class="mb-2 block text-sm font-medium dark:text-surface-0" for="password">
                New Password
              </label>
              <PrimeVuePassword
                id="password"
                v-model="form.fields.password"
                :feedback="false"
                :invalid="form.errors.hasOwnProperty('password')"
                for="password"
                toggle-mask
                fluid
                size="small"
              />
              <div v-if="form.errors.password" class="mt-2 text-red-500">
                {{ form.errors.password }}
              </div>
            </div>
            <div>
              <label
                class="mb-2 block text-sm font-medium dark:text-surface-0"
                for="password_confirmation"
              >
                Confirm Password
              </label>
              <PrimeVuePassword
                id="password_confirmation"
                v-model="form.fields.password_confirmation"
                :feedback="false"
                :invalid="form.errors.hasOwnProperty('password')"
                :autocomplete="false"
                for="password_confirmation"
                toggle-mask
                fluid
                size="small"
              />
              <div v-if="form.errors.password_confirmation" class="mt-2 text-red-500">
                {{ form.errors.password_confirmation }}
              </div>
            </div>
            <div class="md:text-right mt-6">
              <PrimeVuePrimaryButton
                label="Submit"
                size="small"
                type="submit"
                :disabled="form.processing"
                class="w-full md:w-auto"
              />
            </div>
          </form>
        </template>
      </PrimeVueCard>
    </template>
  </FormSection>
</template>
