<script setup lang="ts">
</script>

<template>
  <PrimeVueCard>
    <template #content>
      <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-center">
          <div class="mr-auto">
            <PrimeVueInputGroup>
              <PrimeVueInputGroupAddon>
                <i class="pi pi-search" />
              </PrimeVueInputGroupAddon>
              <slot name="search-bar" />
            </PrimeVueInputGroup>
          </div>
          <div class="ml-auto">
            <slot name="create-button" />
          </div>
        </div>
        <div class="mt-5 border border-surface-300 dark:border-surface-700 sm:mx-0">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-surface-300 dark:divide-surface-700">
              <slot />
            </table>
          </div>
        </div>
        <slot name="pagination" />
      </div>
    </template>
  </PrimeVueCard>
</template>
