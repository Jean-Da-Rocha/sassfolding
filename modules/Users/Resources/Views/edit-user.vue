<script setup lang="ts">
const props = defineProps<{ user: Modules.Users.Data.UserData }>();

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
      <UCard>
        <form class="space-y-6" @submit.prevent="form.submit">
          <div class="space-y-2">
            <label class="block text-sm font-medium" for="email">
              Email address
            </label>
            <UInput
              id="email"
              v-model="form.fields.email"
              autocomplete="email"
              class="w-full"
              :invalid="Boolean(form.errors.email)"
              placeholder="user@example.com"
              size="lg"
              type="email"
            />
            <p
              v-if="form.errors.email"
              class="
                text-sm text-red-600
                dark:text-red-400
              "
            >
              {{ form.errors.email }}
            </p>
          </div>

          <div class="space-y-2">
            <label class="block text-sm font-medium" for="name">
              Full name
            </label>
            <UInput
              id="name"
              v-model="form.fields.name"
              autocomplete="name"
              class="w-full"
              :invalid="Boolean(form.errors.name)"
              placeholder="John Doe"
              size="lg"
              type="text"
            />
            <p
              v-if="form.errors.name"
              class="
                text-sm text-red-600
                dark:text-red-400
              "
            >
              {{ form.errors.name }}
            </p>
          </div>

          <FormButtons :cancel-url="route('users.index')" :is-form-processing="form.processing" />
        </form>
      </UCard>
    </template>
  </FormSection>
</template>
