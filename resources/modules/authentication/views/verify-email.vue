<script setup lang="ts">
const flashStatus = useProperty('flash.status');

const form = useForm({
  fields: {},
  method: 'POST',
  url: route('verification.send'),
});

const isVerificationLinkSent = computed(() => flashStatus.value?.message === 'verification-link-sent');

useHead({ title: 'Verify Email' });
</script>

<template layout="shared::main">
  <Card class="mx-auto mt-8 w-full max-w-[600px]">
    <template #content>
      <div class="mb-4 text-sm">
        Thanks for signing up! Before getting started, could you verify your email address by clicking on the link
        we just emailed to you? If you didn't receive the email, we will gladly send you another.
      </div>

      <div class="mb-4 text-sm font-medium text-green-600 dark:text-green-400" v-if="isVerificationLinkSent">
        A new verification link has been sent to the email address you provided during registration.
      </div>
      <form @submit.prevent="form.submit" class="space-y-4 md:space-y-6">
        <div>
          <Button
            :disabled="form.processing"
            class="w-full"
            label="Resend Verification Email"
            type="submit"
          />
        </div>
      </form>
    </template>
  </Card>
</template>
