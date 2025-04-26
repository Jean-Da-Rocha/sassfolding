<script setup lang="ts">
const form = useForm({
  fields: {},
  method: 'POST',
  url: route('verification.send'),
});

useHead({ title: 'Verify Email' });
</script>

<template layout="shared::guest">
  <PrimeVueCard class="mx-auto w-full max-w-[600px]">
    <template #content>
      <AlertMessage />
      <div class="mb-4 text-sm">
        Thanks for signing up! Before getting started, could you verify your email address by clicking on the link
        we just emailed to you? If you didn't receive the email, we will gladly send you another.
      </div>
      <PrimeVueFluid>
        <form class="space-y-4 md:space-y-6" @submit.prevent="form.submit">
          <div>
            <PrimeVueButton :disabled="form.processing" label="Resend Verification Email" type="submit" />
          </div>
        </form>
      </PrimeVueFluid>
      <div class="text-center">
        <RouterLink :href="route('logout')" method="post" class="text-primary-500 hover:text-primary-700">
          Sign Out
        </RouterLink>
      </div>
    </template>
  </PrimeVueCard>
</template>
