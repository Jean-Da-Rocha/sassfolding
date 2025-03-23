<script setup lang="ts">
import type { TableActionType } from '@/modules/tables/types/table-action-type';

defineProps<{ action: TableActionType }>();
</script>

<template>
  <PrimeVueButton
    v-tooltip.top="action.label"
    :icon="action.style?.icon"
    :label="action.style?.text"
    :rounded="action.style?.rounded"
    :severity="action.style?.severity"
    :size="action.style?.size"
    :variant="action.style?.variant"
    type="button"
    @click="action.execute()"
  />
</template>
