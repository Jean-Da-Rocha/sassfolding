<template>
  <td
    v-if="$slots.default"
    class="
      !lg:pl-4
      block border-b border-surface-300 p-2 text-right text-sm
      sm:pl-6
      lg:table-cell lg:border-b-0 lg:text-left
      dark:border-surface-700
    "
    role="cell"
  >
    <slot />
  </td>
</template>
