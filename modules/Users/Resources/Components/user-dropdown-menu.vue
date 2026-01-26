<script setup lang="ts">
defineProps<{ collapsed: boolean }>();

const { user } = useAuth();

const appConfig = useAppConfig();

const colorMode = useColorMode();

const colors = [
  'amber',
  'blue',
  'cyan',
  'emerald',
  'fuchsia',
  'green',
  'indigo',
  'lime',
  'orange',
  'pink',
  'purple',
  'red',
  'rose',
  'sky',
  'teal',
  'violet',
  'yellow',
] as const;

const neutrals = ['gray', 'neutral', 'slate', 'stone', 'zinc'] as const;

type Color = typeof colors[number];
type NeutralColor = typeof neutrals[number];

onMounted(() => {
  const savedPrimary = localStorage.getItem('theme-primary');
  const savedNeutral = localStorage.getItem('theme-neutral');

  if (savedPrimary) {
    appConfig.ui.colors.primary = savedPrimary;
  }

  if (savedNeutral) {
    appConfig.ui.colors.neutral = savedNeutral;
  }
});

const items = computed(() => {
  return [
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
            children: colors.map((color: Color) => ({
              checked: appConfig.ui.colors.primary === color,
              chip: color,
              label: color,
              onSelect(event: Event) {
                event.preventDefault();

                appConfig.ui.colors.primary = color;

                localStorage.setItem('theme-primary', color);
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
            children: neutrals.map((color: NeutralColor) => ({
              checked: appConfig.ui.colors.neutral === color,
              chip: color === 'neutral' ? 'old-neutral' : color,
              label: color,
              onSelect(event: Event) {
                event.preventDefault();

                appConfig.ui.colors.neutral = color;

                localStorage.setItem('theme-neutral', color);
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
        icon: 'i-heroicons-swatch',
        label: 'Theme',
      },
      {
        children: [
          {
            checked: colorMode.value === 'light',
            icon: 'i-heroicons-sun',
            label: 'Light',
            onSelect(event: Event) {
              event.preventDefault();

              colorMode.value = 'light';
            },
            type: 'checkbox',
          },
          {
            checked: colorMode.value === 'dark',
            icon: 'i-heroicons-moon',
            label: 'Dark',
            onSelect(event: Event) {
              event.preventDefault();

              colorMode.value = 'dark';
            },
            type: 'checkbox',
          },
        ],
        icon: 'i-heroicons-computer-desktop',
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
  ] satisfies DropdownMenuItem[][];
});
</script>

<template>
  <UDropdownMenu
    :content="{ align: 'center', collisionPadding: 12 }"
    :items="items"
    :ui="{ content: collapsed ? 'w-48' : 'w-(--reka-dropdown-menu-trigger-width)' }"
  >
    <UButton
      v-bind="{
        avatar: {
          text: user?.name_initial,
        },
        label: collapsed ? undefined : user?.name,
        trailingIcon: collapsed ? undefined : 'i-heroicons-chevron-up-down',
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

    <!-- @vue-expect-error dynamic slot from Nuxt UI DropdownMenu component. -->
    <template #chip-leading="{ item }">
      <div class="inline-flex size-5 shrink-0 items-center justify-center">
        <span
          class="
            size-2 rounded-full bg-(--chip-light) ring ring-bg
            dark:bg-(--chip-dark)
          "
          :style="{
            '--chip-light': `var(--color-${item.chip}-500)`,
            '--chip-dark': `var(--color-${item.chip}-400)`,
          }"
        />
      </div>
    </template>
  </UDropdownMenu>
</template>
