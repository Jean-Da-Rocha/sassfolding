<template>
  <th
    class="
      text-surface-700 hidden py-3.5 pr-3 pl-4 text-left text-sm font-semibold
      dark:text-surface-0
      sm:pl-6
      lg:table-cell
    "
    role="columnheader"
    scope="col"
  >
    <div class="flex items-center">
      <div>
        <slot name="default" />
      </div>
      <div class="group inline-flex cursor-pointer">
        <slot name="sort" />
      </div>
    </div>
  </th>
</template>
