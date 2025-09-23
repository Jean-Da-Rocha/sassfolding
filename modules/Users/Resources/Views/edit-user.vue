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

<template layout="core::main">
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
                  text-surface-700 mb-2 block text-sm font-medium
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
                  text-surface-700 mb-2 block text-sm font-medium
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
            <FormButtons :cancel-url="route('users.index')" :is-form-processing="form.processing" />
          </form>
        </template>
      </PrimeVueCard>
    </template>
  </FormSection>
</template>
