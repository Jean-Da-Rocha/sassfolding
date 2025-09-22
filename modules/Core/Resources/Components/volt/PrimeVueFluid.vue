<script setup lang="ts">
type Props = {} & /* @vue-ignore */ FluidProps;

defineProps<Props>();
</script>

<template>
  <Fluid unstyled>
    <slot />
  </Fluid>
</template>
