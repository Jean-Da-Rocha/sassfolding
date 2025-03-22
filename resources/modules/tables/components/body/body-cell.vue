<template>
  <td v-if="$slots.default" class="py-4 pl-4 pr-3 text-sm sm:pl-6" role="cell">
    <slot />
  </td>
</template>
