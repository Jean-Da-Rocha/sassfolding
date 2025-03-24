<template>
  <th
    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-surface-700 dark:text-surface-0 sm:pl-6 hidden lg:table-cell"
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
