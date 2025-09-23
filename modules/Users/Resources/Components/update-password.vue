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

<template layout="core::main">
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
          <form
            class="
              space-y-4
              md:space-y-6
            "
            @submit.prevent="form.submit"
          >
            <div>
              <label
                class="
                  dark:text-surface-0
                  mb-2 block text-sm font-medium
                "
                for="current_password"
              >
                Current password
              </label>
              <PrimeVuePassword
                id="current_password"
                v-model="form.fields.current_password"
                :feedback="false"
                fluid
                for="current_password"
                :invalid="form.errors.hasOwnProperty('password')"
                size="small"
                toggle-mask
              />
              <div v-if="form.errors.current_password" class="mt-2 text-red-500">
                {{ form.errors.current_password }}
              </div>
            </div>
            <div>
              <label
                class="
                  dark:text-surface-0
                  mb-2 block text-sm font-medium
                " for="password"
              >
                New Password
              </label>
              <PrimeVuePassword
                id="password"
                v-model="form.fields.password"
                :feedback="false"
                fluid
                for="password"
                :invalid="form.errors.hasOwnProperty('password')"
                size="small"
                toggle-mask
              />
              <div v-if="form.errors.password" class="mt-2 text-red-500">
                {{ form.errors.password }}
              </div>
            </div>
            <div>
              <label
                class="
                  dark:text-surface-0
                  mb-2 block text-sm font-medium
                "
                for="password_confirmation"
              >
                Confirm Password
              </label>
              <PrimeVuePassword
                id="password_confirmation"
                v-model="form.fields.password_confirmation"
                autocomplete="off"
                :feedback="false"
                fluid
                for="password_confirmation"
                :invalid="form.errors.hasOwnProperty('password')"
                size="small"
                toggle-mask
              />
              <div
                v-if="form.errors.password_confirmation"
                class="mt-2 text-red-500"
              >
                {{ form.errors.password_confirmation }}
              </div>
            </div>
            <div
              class="
                mt-6
                md:text-right
              "
            >
              <PrimeVuePrimaryButton
                class="
                  w-full
                  md:w-auto
                "
                :disabled="form.processing"
                label="Submit"
                size="small"
                type="submit"
              />
            </div>
          </form>
        </template>
      </PrimeVueCard>
    </template>
  </FormSection>
</template>
