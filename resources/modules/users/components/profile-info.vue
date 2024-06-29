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
      <PrimeVueMessage :life="5000" :sticky="false" class="-mt-3" severity="success" v-if="flashStatus?.message === 'profile-information-updated'">
        Profile successfully updated
      </PrimeVueMessage>
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
          <label
            class="mb-2 block text-sm font-medium"
            for="name"
          >
            Name
          </label>
          <PrimeVueInputText
            :invalid="form.errors.hasOwnProperty('name')"
            autofocus
            class="w-full"
            id="name"
            type="text"
            v-model="form.fields.name"
          />
          <div class="mt-2 text-red-500" v-if="form.errors.name">
            {{ form.errors.name }}
          </div>
        </div>
        <div>
          <PrimeVueButton :disabled="form.submit" class="w-full" label="Submit" type="submit" />
        </div>
      </form>
    </template>
  </PrimeVueCard>
</template>
