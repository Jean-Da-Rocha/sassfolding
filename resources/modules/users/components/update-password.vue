<script setup lang="ts">
const flashStatusMessage = useProperty('flash.status');

const form = useForm({
  errorBag: 'updatePassword',
  fields: {
    current_password: '',
    password: '',
    password_confirmation: '',
  },
  method: 'PUT',
  url: route('user-password.update'),
});
</script>

<template>
  <Card class="mx-auto mt-8 w-full max-w-[600px]">
    <template #content>
      <Message
        :life="5000" :sticky="false" class="-mt-3" severity="success"
        v-if="flashStatusMessage === 'password-updated'"
      >
        Password successfully updated
      </Message>
      <form @submit.prevent="form.submit" class="space-y-4 md:space-y-6">
        <div>
          <label
            class="mb-2 block text-sm font-medium"
            for="current_password"
          >
            Current password
          </label>
          <Password
            :feedback="false"
            :pt="{
              root: { class: ['w-full relative', { 'border-red-500': form.errors.current_password }] },
            }"
            :required="true"
            for="current_password"
            id="current_password"
            input-class="w-full"
            toggle-mask
            v-model="form.fields.current_password"
          />
          <div class="mt-2 text-red-500" v-if="form.errors.current_password">
            {{ form.errors.current_password }}
          </div>
        </div>
        <div>
          <label
            class="mb-2 block text-sm font-medium"
            for="password"
          >
            New Password
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
            class="w-full"
            label="Submit"
            type="submit"
          />
        </div>
      </form>
    </template>
  </Card>
</template>
