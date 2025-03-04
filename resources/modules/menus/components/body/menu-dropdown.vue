<script setup lang="ts">
defineProps<{ title: string }>();

const isDropdownMenuCollapsed = ref<boolean>(true);
</script>

<template>
  <nav>
    <ul class="m-0 list-none px-4">
      <li>
        <div
          class="flex cursor-pointer items-center justify-between rounded-md p-3 text-surface-700 hover:bg-surface-100 dark:text-surface-0/80 dark:hover:bg-surface-700"
          @click="() => isDropdownMenuCollapsed = !isDropdownMenuCollapsed"
        >
          <h2 class="text-sm font-bold uppercase">
            {{ title }}
          </h2>
          <HeroiconsChevronDown
            :class="{ 'rotate-180': isDropdownMenuCollapsed }"
            class="transform transition-transform duration-200"
          />
        </div>
        <Transition
          enter-active-class="transform transition duration-200 ease-in-out"
          enter-from-class="scale-y-0 opacity-0"
          enter-to-class="scale-y-100 opacity-100"
          leave-active-class="transform transition duration-200 ease-in-out"
          leave-from-class="scale-y-100 opacity-100"
          leave-to-class="scale-y-0 opacity-0"
        >
          <div
            v-show="isDropdownMenuCollapsed"
            class="overflow-hidden"
          >
            <slot />
          </div>
        </Transition>
      </li>
    </ul>
  </nav>
</template>
