<script setup lang="ts">
export type FlashMessageReturnType = {
  message: string;
  severity: string;
};

const properties = useProperties<Modules.Core.Data.SharedData>();
const closedAlerts = ref<Set<string>>(new Set());

const flashMessages = computed<FlashMessageReturnType[]>(() => {
  if (!properties.flash) {
    return [];
  }

  return (Object.entries(properties.flash) as [string, string | null][])
    .filter(([severity, message]) =>
      message !== null && message.length > 0 && !closedAlerts.value.has(severity),
    )
    .map(([severity, message]) => ({
      message: message as string,
      severity,
    }));
});

function closeAlert(severity: string): void {
  closedAlerts.value.add(severity);
}
</script>

<template>
  <div v-for="{ message, severity } in flashMessages" :key="severity">
    <UAlert
      close
      close-icon="i-heroicons-x-mark"
      :color="severity"
      :title="message"
      @update:open="(isOpen: boolean) => !isOpen && closeAlert(severity)"
    />
  </div>
</template>
