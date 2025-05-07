<script setup lang="ts">
const form = useForm<{ email: string; name: string; password: string; password_confirmation: string }>({
  fields: {
    email: '',
    name: '',
    password: '',
    password_confirmation: '',
  },
  method: 'POST',
  preserveState: true,
  url: route('users.store'),
});

useHead({ title: 'User Creation' });
</script>

<template layout="shared::main">
  <FormSection>
    <template #title>
      User Creation
    </template>

    <template #description>
      Create a new user for your application.
    </template>

    <template #form>
      <PrimeVueCard>
        <template #content>
          <form
            class="
              space-y-4
              md:space-y-6
            "
            @keydown.enter.prevent="form.submit"
            @submit.prevent="form.submit"
          >
            <div>
              <label
                class="
                  mb-2 block text-sm font-medium text-surface-700
                  dark:text-surface-0
                "
                for="email"
              >
                Email
              </label>
              <PrimeVueInputText
                id="email"
                v-model="form.fields.email"
                :autofocus="true"
                fluid
                :invalid="form.errors.hasOwnProperty('email')"
                size="small"
                type="text"
              />
              <div v-if="form.errors.email" class="mt-2 text-red-500">
                {{ form.errors.email }}
              </div>
            </div>
            <div>
              <label
                class="
                  mb-2 block text-sm font-medium text-surface-700
                  dark:text-surface-0
                "
                for="name"
              >
                Name
              </label>
              <PrimeVueInputText
                id="name"
                v-model="form.fields.name"
                fluid
                :invalid="form.errors.hasOwnProperty('name')"
                size="small"
                type="text"
              />
              <div v-if="form.errors.name" class="mt-2 text-red-500">
                {{ form.errors.name }}
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
              <label class="mb-2 block text-sm font-medium" for="password_confirmation">
                Confirm Password
              </label>
              <PrimeVuePassword
                id="password_confirmation"
                v-model="form.fields.password_confirmation"
                :feedback="false"
                fluid
                for="password_confirmation"
                :invalid="form.errors.hasOwnProperty('password_confirmation')"
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
            <FormButtons :cancel-url="route('users.index')" :is-form-processing="form.processing" />
          </form>
        </template>
      </PrimeVueCard>
    </template>
  </FormSection>
</template>
