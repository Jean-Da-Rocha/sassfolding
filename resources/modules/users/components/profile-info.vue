<script setup lang="ts">
const props = defineProps<{ user: App.Data.UserData | null }>();

const form = useForm<{ email: string; name: string }>({
  fields: {
    email: props.user?.email ?? '',
    name: props.user?.name ?? '',
  },
  method: 'PUT',
  preserveState: false,
  url: route('user-profile-information.update'),
});
</script>

<template>
  <FormSection>
    <template #title>
      Profile Information
    </template>

    <template #description>
      Update your account's profile information and email address.
    </template>

    <template #form>
      <PrimeVueCard>
        <template #content>
          <form class="space-y-4 md:space-y-6" @submit.prevent="form.submit">
            <div>
              <label class="mb-2 block text-sm font-medium" for="email">
                Email
              </label>
              <PrimeVueInputText
                id="email"
                v-model="form.fields.email"
                :invalid="form.errors.hasOwnProperty('email')"
                :autofocus="true"
                fluid
                type="text"
              />
              <div v-if="form.errors.email" class="mt-2 text-red-500">
                {{ form.errors.email }}
              </div>
            </div>
            <div>
              <label class="mb-2 block text-sm font-medium" for="name">
                Name
              </label>
              <PrimeVueInputText
                id="name"
                v-model="form.fields.name"
                :invalid="form.errors.hasOwnProperty('name')"
                :autofocus="true"
                fluid
                type="text"
              />
              <div v-if="form.errors.name" class="mt-2 text-red-500">
                {{ form.errors.name }}
              </div>
            </div>
            <div class="text-right">
              <PrimeVuePrimaryButton :disabled="form.processing" label="Submit" size="small" type="submit" />
            </div>
          </form>
        </template>
      </PrimeVueCard>
    </template>
  </FormSection>
</template>
