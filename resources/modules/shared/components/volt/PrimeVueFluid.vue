<script setup lang="ts">
import type { FluidProps } from 'primevue/fluid';
import Fluid from 'primevue/fluid';

type Props = {} & /* @vue-ignore */ FluidProps;
defineProps<Props>();
</script>

<template>
  <Fluid unstyled>
    <slot />
  </Fluid>
</template>
