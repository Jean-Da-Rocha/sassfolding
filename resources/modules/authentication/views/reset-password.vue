<script setup lang="ts">
const props = defineProps<{
  email: string;
  token: string;
}>();

const form = useForm({
  fields: {
    email: props.email,
    password: '',
    password_confirmation: '',
    token: props.token,
  },
  method: 'POST',
  url: route('password.update'),
});

useHead({ title: 'Reset Password' });
</script>

<template layout="shared::main">
  <Card class="mx-auto mt-8 w-full max-w-[600px]">
    <template #content>
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
            for="password"
          >
            Password
          </label>
          <Password
            :feedback="false"
            :pt="{
              root: { class: ['w-full relative', { 'border-red-500': form.errors.password }] },
            }"
            :required="true"
            for="password"
            id="password"
            input-class="w-full"
            toggle-mask
            v-model="form.fields.password"
          />
          <div class="mt-2 text-red-500" v-if="form.errors.password">
            {{ form.errors.password }}
          </div>
        </div>
        <div>
          <label
            class="mb-2 block text-sm font-medium"
            for="password_confirmation"
          >
            Confirm Password
          </label>
          <Password
            :feedback="false"
            :pt="{
              root: { class: ['w-full relative', { 'border-red-500': form.errors.password_confirmation }] },
            }"
            :required="true"
            for="password_confirmation"
            id="password_confirmation"
            input-class="w-full"
            toggle-mask
            v-model="form.fields.password_confirmation"
          />
          <div class="mt-2 text-red-500" v-if="form.errors.password_confirmation">
            {{ form.errors.password_confirmation }}
          </div>
        </div>
        <div>
          <Button
            :disabled="form.processing"
            class="w-full"
            label="Reset Password"
            type="submit"
          />
        </div>
      </form>
    </template>
  </Card>
</template>
