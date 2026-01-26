<template>
  <div
    class="
      flex justify-between
      md:col-span-1
    "
  >
    <div class="lg:px-4">
      <h3 class="text-lg font-medium">
        <slot name="title" />
      </h3>

      <p class="mt-1 text-sm">
        <slot name="description" />
      </p>
    </div>

    <div class="lg:px-4">
      <slot name="aside" />
    </div>
  </div>
</template>
