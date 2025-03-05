<script setup lang="ts">
const darkMode = useDark();
const toggleDarkMode = useToggle(darkMode);
</script>

<template layout="shared::main">
  <FormSection>
    <template #title>
      Theming
    </template>

    <template #description>
      Change the look and feel of the application with dark mode.
    </template>

    <template #form>
      <PrimeVueCard>
        <template #content>
          <div class="text-right">
            <PrimeVueButton @click="toggleDarkMode()">
              <HeroiconsSun v-if="darkMode" />
              <HeroiconsMoon v-else />
              <span class="mx-2 text-sm font-medium">
                {{ darkMode ? 'Toggle Light Mode' : 'Toggle Dark Mode' }}
              </span>
            </PrimeVueButton>
          </div>
        </template>
      </PrimeVueCard>
    </template>
  </FormSection>
</template>
