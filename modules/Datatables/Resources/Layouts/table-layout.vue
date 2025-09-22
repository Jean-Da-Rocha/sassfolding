<script setup lang="ts">
</script>

<template>
  <PrimeVueCard>
    <template #content>
      <div
        class="
          px-4
          sm:px-6
          lg:px-8
        "
      >
        <div class="lg:flex lg:items-center">
          <div class="lg:mr-auto">
            <div class="flex flex-auto items-stretch">
              <slot name="search-bar" />
            </div>
          </div>
          <div
            class="
              mt-3
              lg:mt-0 lg:ml-auto
            "
          >
            <slot name="create-button" />
          </div>
        </div>
        <div
          class="
            mt-5 border-r border-b border-l border-surface-300
            sm:mx-0
            lg:border
            dark:border-surface-700
          "
        >
          <div class="overflow-x-auto">
            <table
              class="
                w-full table-auto divide-y divide-surface-300
                dark:divide-surface-700
              "
            >
              <slot />
            </table>
          </div>
        </div>
        <slot name="pagination" />
      </div>
    </template>
  </PrimeVueCard>
</template>
