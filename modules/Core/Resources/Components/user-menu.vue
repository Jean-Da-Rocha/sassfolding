<script setup lang="ts">
import type { DropdownMenuItem } from '@nuxt/ui';

defineProps<{
  collapsed?: boolean;
}>();

const colorMode = useColorMode();
const appConfig = useAppConfig();

const colors = ['red', 'orange', 'amber', 'yellow', 'lime', 'green', 'emerald', 'teal', 'cyan', 'sky', 'blue', 'indigo', 'violet', 'purple', 'fuchsia', 'pink', 'rose'];
const neutrals = ['slate', 'gray', 'zinc', 'neutral', 'stone'];

const user = ref({
  avatar: {
    alt: 'Benjamin Canac',
    src: 'https://github.com/benjamincanac.png',
  },
  name: 'Benjamin Canac',
});

const items = computed<DropdownMenuItem[][]>(() => ([
  [
    {
      avatar: user.value.avatar,
      label: user.value.name,
      type: 'label',
    },
  ],
  [
    {
      icon: 'i-heroicons-user',
      label: 'Profile',
      onSelect: () => router.get(route('profile')),
    },
  ],
  [
    {
      children: [
        {
          children: colors.map(color => ({
            checked: appConfig.ui.colors.primary === color,
            chip: color,
            label: color,
            onSelect: (e) => {
              e.preventDefault();

              appConfig.ui.colors.primary = color;
            },
            slot: 'chip',
            type: 'checkbox',
          })),
          chip: appConfig.ui.colors.primary,
          content: {
            align: 'center',
            collisionPadding: 16,
          },
          label: 'Primary',
          slot: 'chip',
        },
        {
          children: neutrals.map(color => ({
            checked: appConfig.ui.colors.neutral === color,
            chip: color === 'neutral' ? 'old-neutral' : color,
            label: color,
            onSelect: (e) => {
              e.preventDefault();

              appConfig.ui.colors.neutral = color;
            },
            slot: 'chip',
            type: 'checkbox',
          })),
          chip: appConfig.ui.colors.neutral === 'neutral' ? 'old-neutral' : appConfig.ui.colors.neutral,
          content: {
            align: 'end',
            collisionPadding: 16,
          },
          label: 'Neutral',
          slot: 'chip',
        },
      ],
      icon: 'i-lucide-palette',
      label: 'Theme',
    },
    {
      children: [{
        checked: colorMode.value === 'light',
        icon: 'i-lucide-sun',
        label: 'Light',
        onSelect(e: Event) {
          e.preventDefault();

          colorMode.preference = 'light';
        },
        type: 'checkbox',
      }, {
        checked: colorMode.value === 'dark',
        icon: 'i-lucide-moon',
        label: 'Dark',
        onSelect(e: Event) {
          e.preventDefault();
        },
        onUpdateChecked(checked: boolean) {
          if (checked) {
            colorMode.preference = 'dark';
          }
        },
        type: 'checkbox',
      }],
      icon: 'i-lucide-sun-moon',
      label: 'Appearance',
    },
  ],
  [
    {
      icon: 'i-heroicons-arrow-left-on-rectangle',
      label: 'Logout',
      onSelect: () => router.post(route('logout')),
    },
  ],
]));
</script>

<template>
  <UDropdownMenu
    :content="{ align: 'center', collisionPadding: 12 }"
    :items="items"
    :ui="{ content: collapsed ? 'w-48' : 'w-(--reka-dropdown-menu-trigger-width)' }"
  >
    <UButton
      v-bind="{
        ...user,
        label: collapsed ? undefined : user?.name,
        trailingIcon: collapsed ? undefined : 'i-lucide-chevrons-up-down',
      }"
      block
      class="data-[state=open]:bg-elevated"
      color="neutral"
      :square="collapsed"
      :ui="{
        trailingIcon: 'text-dimmed',
      }"
      variant="ghost"
    />

    <template #chip-leading="{ item }">
      <div class="inline-flex size-5 shrink-0 items-center justify-center">
        <span
          class="
            size-2 rounded-full bg-(--chip-light) ring ring-bg
            dark:bg-(--chip-dark)
          "
          :style="{
            '--chip-light': `var(--color-${(item as any).chip}-500)`,
            '--chip-dark': `var(--color-${(item as any).chip}-400)`,
          }"
        />
      </div>
    </template>
  </UDropdownMenu>
</template>
