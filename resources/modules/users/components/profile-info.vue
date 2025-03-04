<script setup lang="ts">
const props = defineProps<{
  user: App.Data.UserData | null;
}>();

const flashStatus = useProperty('flash.status');

const form = useForm<{ email?: string; name?: string }>({
  errorBag: 'updateProfileInformation',
  fields: {
    email: props.user?.email,
    name: props.user?.name,
  },
  method: 'PUT',
  preserveState: false,
  url: route('user-profile-information.update'),
});
</script>

<template>
  <PrimeVueCard class="mx-auto mt-8 w-full max-w-[600px]">
    <template #content>
      <PrimeVueMessage v-if="flashStatus?.message === 'profile-information-updated'" :life="5000" :sticky="false" class="-mt-3" severity="success">
        Profile successfully updated
      </PrimeVueMessage>
      <form class="space-y-4 md:space-y-6" @submit.prevent="form.submit">
        <div>
          <label
            class="mb-2 block text-sm font-medium"
            for="email"
          >
            Email
          </label>
          <PrimeVueInputText
            id="email"
            v-model="form.fields.email"
            :invalid="form.errors.hasOwnProperty('email')"
            autofocus
            class="w-full"
            type="text"
          />
          <div v-if="form.errors.email" class="mt-2 text-red-500">
            {{ form.errors.email }}
          </div>
        </div>
        <div>
          <label
            class="mb-2 block text-sm font-medium"
            for="name"
          >
            Name
          </label>
          <PrimeVueInputText
            id="name"
            v-model="form.fields.name"
            :invalid="form.errors.hasOwnProperty('name')"
            autofocus
            class="w-full"
            type="text"
          />
          <div v-if="form.errors.name" class="mt-2 text-red-500">
            {{ form.errors.name }}
          </div>
        </div>
        <div>
          <PrimeVueButton :disabled="form.processing" class="w-full" label="Submit" type="submit" />
        </div>
      </form>
    </template>
  </PrimeVueCard>
</template>
