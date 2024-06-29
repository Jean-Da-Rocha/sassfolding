<template>
  <div class="md:grid md:grid-cols-3 md:gap-6">
    <SectionTitle>
      <template #title>
        <slot name="title" />
      </template>
      <template #description>
        <slot name="description" />
      </template>
    </SectionTitle>

    <div class="mt-5 md:col-span-2 md:mt-0">
      <div class="xs:py-6">
        <div class="grid gap-6">
          <slot name="form" />
        </div>
      </div>
    </div>
  </div>
</template>
