<script setup lang="ts">
import type { ButtonGroupPassThroughOptions, ButtonGroupProps } from 'primevue/buttongroup';
import ButtonGroup from 'primevue/buttongroup';
import { ref } from 'vue';
import { ptViewMerge } from './utils';

type Props = {} & /* @vue-ignore */ ButtonGroupProps;

defineProps<Props>();

const theme = ref<ButtonGroupPassThroughOptions>({
  root: `*:rounded-none *:first:rounded-s-md *:last:rounded-e-md
        *:focus-visible:relative *:focus-visible:z-10 *:not-last:border-r-0`,
});
</script>

<template>
  <ButtonGroup
    :pt="theme"
    :pt-options="{
      mergeProps: ptViewMerge,
    }"
    unstyled
  >
    <template v-for="(_, slotName) in $slots" #[slotName]="slotProps">
      <slot :name="slotName" v-bind="slotProps ?? {}" />
    </template>
  </ButtonGroup>
</template>
