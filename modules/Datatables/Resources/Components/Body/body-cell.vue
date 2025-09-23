<template>
  <td
    v-if="$slots.default"
    class="
      border-surface-300 block border-b p-2 text-right text-sm
      dark:border-surface-700
      sm:pl-6
      lg:table-cell lg:border-b-0 lg:pl-4 lg:text-left
    "
    role="cell"
  >
    <slot />
  </td>
</template>
