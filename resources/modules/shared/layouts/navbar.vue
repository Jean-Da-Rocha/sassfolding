<script setup lang="ts">
const { isUserAuthenticated } = useSecurity();
const { current } = useRoute();
</script>

<template>
  <div>
    <nav class="bg-primary-500 p-4">
      <div class="flex items-center justify-between">
        <div class="text-lg font-bold text-white">
          <RouterLink href="/">
            LVHP Stack
          </RouterLink>
        </div>
        <div class="flex items-center space-x-4">
          <div v-if="isUserAuthenticated">
            <RouterLink :class="{ 'font-bold': current === 'profile' }" :href="route('login')" class="text-white">
              My profile
            </RouterLink>
            <RouterLink :href="route('logout')" as="button" class="text-white hover:font-bold" method="post">
              Log Out
            </RouterLink>
          </div>
          <div v-else>
            <RouterLink :class="{ 'font-bold': current === 'login' }" :href="route('login')" class="text-white">
              Sign In
            </RouterLink>
            <RouterLink :class="{ 'font-bold': current === 'register' }" :href="route('register')" class="text-white">
              Register
            </RouterLink>
          </div>
        </div>
      </div>
    </nav>
  </div>
</template>
