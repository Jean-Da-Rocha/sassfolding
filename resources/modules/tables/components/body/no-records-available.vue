<script setup lang="ts">
defineProps<{ colspan: number }>();
</script>

<template>
  <tr role="row">
    <td
      class="
        py-4 pr-3 pl-4 text-center text-sm
        sm:pl-6
      " :colspan="colspan" role="cell"
    >
      There are no records available.
    </td>
  </tr>
</template>
