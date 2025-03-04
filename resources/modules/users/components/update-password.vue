<script setup lang="ts">
const form = useForm<{ current_password: string; password: string; password_confirmation: string }>({
  errorBag: 'updatePassword',
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
              <label
                class="mb-2 block text-sm font-medium text-surface-700 dark:text-surface-0/80"
                for="current_password"
              >
                Current password
              </label>
              <PrimeVuePassword
                id="current_password"
                v-model="form.fields.current_password"
                :feedback="false"
                :input-props="{ autocomplete: 'false' }"
                :invalid="form.errors.hasOwnProperty('password')"
                class="w-full"
                for="current_password"
                input-class="w-full"
                toggle-mask
              />
              <div v-if="form.errors.current_password" class="mt-2 text-red-500">
                {{ form.errors.current_password }}
              </div>
            </div>
            <div>
              <label
                class="mb-2 block text-sm font-medium text-surface-700 dark:text-surface-0/80"
                for="password"
              >
                New Password
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
                class="mb-2 block text-sm font-medium text-surface-700 dark:text-surface-0/80"
                for="password_confirmation"
              >
                Confirm Password
              </label>
              <PrimeVuePassword
                id="password_confirmation"
                v-model="form.fields.password_confirmation"
                :feedback="false"
                :input-props="{ autocomplete: 'false' }"
                :invalid="form.errors.hasOwnProperty('password')"
                class="w-full"
                for="password_confirmation"
                input-class="w-full"
                toggle-mask
              />
              <div v-if="form.errors.password_confirmation" class="mt-2 text-red-500">
                {{ form.errors.password_confirmation }}
              </div>
            </div>
            <div class="text-right">
              <PrimeVueButton :disabled="form.processing" label="Submit" size="small" type="submit" />
            </div>
          </form>
        </template>
      </PrimeVueCard>
    </template>
  </FormSection>
</template>
