<template>
  <td
    v-if="$slots.default"
    class="border-b border-surface-300 dark:border-surface-700 p-2 text-sm text-right lg:text-left sm:pl-6 block !lg:pl-4 lg:table-cell lg:border-b-0"
    role="cell"
  >
    <slot />
  </td>
</template>
