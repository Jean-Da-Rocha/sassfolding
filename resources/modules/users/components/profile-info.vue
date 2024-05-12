<script setup lang="ts">
const props = defineProps<{
  user: App.Data.UserData | null;
}>();

const flashStatus = useProperty('flash.status');

const form = useForm({
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
  <Card class="mx-auto mt-8 w-full max-w-[600px]">
    <template #content>
      <Message :life="5000" :sticky="false" class="-mt-3" severity="success" v-if="flashStatus?.message === 'profile-information-updated'">
        Profile successfully updated
      </Message>
      <form @submit.prevent="form.submit" class="space-y-4 md:space-y-6">
        <div>
          <label
            class="mb-2 block text-sm font-medium"
            for="email"
          >
            Email
          </label>
          <InputText
            :class="{ 'border-red-500': form.errors.email }"
            :required="true"
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
          <InputText
            :class="{ 'border-red-500': form.errors.name }"
            :required="true"
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
          <Button
            class="w-full"
            label="Submit"
            type="submit"
          />
        </div>
      </form>
    </template>
  </Card>
</template>
