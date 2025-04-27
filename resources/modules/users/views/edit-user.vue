<script setup lang="ts">
const props = defineProps<{ user: App.Data.UserData }>();

const form = useForm<{ email: string; name: string }>({
  fields: {
    email: props.user.email,
    name: props.user.name,
  },
  method: 'PUT',
  preserveState: true,
  url: route('users.update', { user: props.user.id }),
});

useHead({ title: 'User Modification' });
</script>

<template layout="shared::main">
  <FormSection>
    <template #title>
      User Modification
    </template>

    <template #description>
      Edit <span class="font-bold">{{ user.name }}</span> information.
    </template>

    <template #form>
      <PrimeVueCard>
        <template #content>
          <form class="space-y-4 md:space-y-6" @keydown.enter.prevent="form.submit" @submit.prevent="form.submit">
            <div>
              <label class="mb-2 block text-sm font-medium text-surface-700 dark:text-surface-0" for="email">
                Email
              </label>
              <PrimeVueInputText
                id="email"
                v-model="form.fields.email"
                :invalid="form.errors.hasOwnProperty('email')"
                :autofocus="true"
                type="text"
                fluid
                size="small"
              />
              <div v-if="form.errors.email" class="mt-2 text-red-500">
                {{ form.errors.email }}
              </div>
            </div>
            <div>
              <label class="mb-2 block text-sm font-medium text-surface-700 dark:text-surface-0" for="name">
                Name
              </label>
              <PrimeVueInputText
                id="name"
                v-model="form.fields.name"
                :invalid="form.errors.hasOwnProperty('name')"
                type="text"
                fluid
                size="small"
              />
              <div v-if="form.errors.name" class="mt-2 text-red-500">
                {{ form.errors.name }}
              </div>
            </div>
            <div class="md:text-right mt-6">
              <PrimeVuePrimaryButton
                label="Save"
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
