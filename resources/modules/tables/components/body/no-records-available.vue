<script setup lang="ts">
defineProps<{ colspan: number }>();
</script>

<template>
  <tr role="row">
    <td :colspan="colspan" class="py-4 pl-4 pr-3 text-center text-sm sm:pl-6" role="cell">
      There are no records available.
    </td>
  </tr>
</template>
