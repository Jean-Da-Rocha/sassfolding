<script setup lang="ts">
const appName = useProperty<string>('app.name');
const initial = computed(() => appName.value?.charAt(0).toUpperCase() ?? 'S');
</script>

<template>
  <svg
    class="shrink-0"
    viewBox="0 0 128 128"
    xmlns="http://www.w3.org/2000/svg"
  >
    <rect
      fill="var(--ui-primary)"
      height="128"
      rx="24"
      width="128"
    />
    <text
      dominant-baseline="central"
      fill="white"
      font-family="system-ui, -apple-system, sans-serif"
      font-size="64"
      font-weight="700"
      text-anchor="middle"
      x="64"
      y="64"
    >
      {{ initial }}
    </text>
  </svg>
</template>
