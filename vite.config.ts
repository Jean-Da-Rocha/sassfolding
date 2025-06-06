import { readFileSync } from 'node:fs';
import path from 'node:path';
import process from 'node:process';
import { PrimeVueResolver } from '@primevue/auto-import-resolver';
import hybridly from 'hybridly/vite';
import IconsResolver from 'unplugin-icons/resolver';
import { defineConfig, loadEnv } from 'vite';

export default defineConfig(({ mode }) => {
  const env = loadEnv(mode, process.cwd());
  const composeProjectName = env.VITE_APP_NAME;
  const certPath = `docker/traefik/certs/${composeProjectName}`;

  return {
    build: {
      sourcemap: false,
    },
    plugins: [
      hybridly({
        autoImports: {
          dirs: ['resources/modules/**'],
          imports: [
            {
              from: 'hybridly',
              imports: ['NavigationResponse', 'RouteName'],
              type: true,
            },
            {
              from: 'primevue/accordion',
              imports: ['AccordionPassThroughOptions', 'AccordionProps'],
              type: true,
            },
            {
              from: 'primevue/accordioncontent',
              imports: ['AccordionContentPassThroughOptions', 'AccordionContentProps'],
              type: true,
            },
            {
              from: 'primevue/accordionheader',
              imports: ['AccordionHeaderPassThroughOptions', 'AccordionHeaderProps'],
              type: true,
            },
            {
              from: 'primevue/accordionpanel',
              imports: ['AccordionPanelPassThroughOptions', 'AccordionPanelProps'],
              type: true,
            },
            {
              from: 'primevue/autocomplete',
              imports: ['AutoCompletePassThroughOptions', 'AutoCompleteProps'],
              type: true,
            },
            {
              from: 'primevue/avatar',
              imports: ['AvatarPassThroughOptions', 'AvatarProps'],
              type: true,
            },
            {
              from: 'primevue/avatargroup',
              imports: ['AvatarGroupPassThroughOptions', 'AvatarGroupProps'],
              type: true,
            },
            {
              from: 'primevue/badge',
              imports: ['BadgePassThroughOptions', 'BadgeProps'],
              type: true,
            },
            {
              from: 'primevue/breadcrumb',
              imports: ['BreadcrumbPassThroughOptions', 'BreadcrumbProps'],
              type: true,
            },
            {
              from: 'primevue/button',
              imports: ['ButtonPassThroughOptions', 'ButtonProps'],
              type: true,
            },
            {
              from: 'primevue/buttongroup',
              imports: ['ButtonGroupPassThroughOptions', 'ButtonGroupProps'],
              type: true,
            },
            {
              from: 'primevue/card',
              imports: ['CardPassThroughOptions', 'CardProps'],
              type: true,
            },
            {
              from: 'primevue/chip',
              imports: ['ChipPassThroughOptions', 'ChipProps'],
              type: true,
            },
            {
              from: 'primevue/checkbox',
              imports: ['CheckboxPassThroughOptions', 'CheckboxProps'],
              type: true,
            },
            {
              from: 'primevue/confirmdialog',
              imports: ['ConfirmDialogPassThroughOptions', 'ConfirmDialogProps'],
              type: true,
            },
            {
              from: 'primevue/dataview',
              imports: ['DataViewPassThroughOptions', 'DataViewProps'],
              type: true,
            },
            {
              from: 'primevue/dialog',
              imports: ['DialogPassThroughOptions', 'DialogProps'],
              type: true,
            },
            {
              from: 'primevue/divider',
              imports: ['DividerPassThroughOptions', 'DividerProps'],
              type: true,
            },
            {
              from: 'primevue/drawer',
              imports: ['DrawerPassThroughOptions', 'DrawerProps'],
              type: true,
            },
            {
              from: 'primevue/fieldset',
              imports: ['FieldsetPassThroughOptions', 'FieldsetProps'],
              type: true,
            },
            {
              from: 'primevue/fluid',
              imports: ['FluidProps'],
              type: true,
            },
            {
              from: 'primevue/inplace',
              imports: ['InplacePassThroughOptions', 'InplaceProps'],
              type: true,
            },
            {
              from: 'primevue/inputmask',
              imports: ['InputMaskPassThroughOptions', 'InputMaskProps'],
              type: true,
            },
            {
              from: 'primevue/inputotp',
              imports: ['InputOtpPassThroughOptions', 'InputOtpProps'],
              type: true,
            },
            {
              from: 'primevue/inputnumber',
              imports: ['InputNumberPassThroughOptions', 'InputNumberProps'],
              type: true,
            },
            {
              from: 'primevue/inputtext',
              imports: ['InputTextPassThroughOptions', 'InputTextProps'],
              type: true,
            },
            {
              from: 'primevue/listbox',
              imports: ['ListboxPassThroughOptions', 'ListboxProps'],
              type: true,
            },
            {
              from: 'primevue/menu',
              imports: ['Menu', 'MenuPassThroughOptions', 'MenuProps'],
              type: true,
            },
            {
              from: 'primevue/message',
              imports: ['MessagePassThroughOptions', 'MessageProps'],
              type: true,
            },
            {
              from: 'primevue/metergroup',
              imports: ['MeterGroupPassThroughOptions', 'MeterGroupProps'],
              type: true,
            },
            {
              from: 'primevue/multiselect',
              imports: ['MultiSelectPassThroughOptions', 'MultiSelectProps'],
              type: true,
            },
            {
              from: 'primevue/paginator',
              imports: ['PaginatorPassThroughOptions', 'PaginatorProps', 'PageState'],
              type: true,
            },
            {
              from: 'primevue/panel',
              imports: ['PanelPassThroughOptions', 'PanelProps'],
              type: true,
            },
            {
              from: 'primevue/password',
              imports: ['PasswordPassThroughOptions', 'PasswordProps'],
              type: true,
            },
            {
              from: 'primevue/popover',
              imports: ['Popover', 'PopoverPassThroughOptions', 'PopoverProps'],
              type: true,
            },
            {
              from: 'primevue/progressbar',
              imports: ['ProgressBarPassThroughOptions', 'ProgressBarProps'],
              type: true,
            },
            {
              from: 'primevue/radiobutton',
              imports: ['RadioButtonPassThroughOptions', 'RadioButtonProps'],
              type: true,
            },
            {
              from: 'primevue/rating',
              imports: ['RatingPassThroughOptions', 'RatingProps'],
              type: true,
            },
            {
              from: 'primevue/select',
              imports: ['SelectPassThroughOptions', 'SelectProps'],
              type: true,
            },
            {
              from: 'primevue/selectbutton',
              imports: ['SelectButtonPassThroughOptions', 'SelectButtonProps'],
              type: true,
            },
            {
              from: 'primevue/skeleton',
              imports: ['SkeletonPassThroughOptions', 'SkeletonProps'],
              type: true,
            },
            {
              from: 'primevue/slider',
              imports: ['SliderPassThroughOptions', 'SliderProps'],
              type: true,
            },
            {
              from: 'primevue/step',
              imports: ['StepPassThroughOptions', 'StepProps'],
              type: true,
            },
            {
              from: 'primevue/splitter',
              imports: ['SplitterPassThroughOptions', 'SplitterProps'],
              type: true,
            },
            {
              from: 'primevue/steplist',
              imports: ['StepListPassThroughOptions', 'StepListProps'],
              type: true,
            },
            {
              from: 'primevue/stepitem',
              imports: ['StepItemPassThroughOptions', 'StepItemProps'],
              type: true,
            },
            {
              from: 'primevue/steppanel',
              imports: ['StepPanelPassThroughOptions', 'StepPanelProps'],
              type: true,
            },
            {
              from: 'primevue/steppanels',
              imports: ['StepPanelsPassThroughOptions', 'StepPanelsProps'],
              type: true,
            },
            {
              from: 'primevue/stepper',
              imports: ['StepperPassThroughOptions', 'StepperProps'],
              type: true,
            },
            {
              from: 'primevue/tabs',
              imports: ['TabPassThroughOptions', 'TabProps'],
              type: true,
            },
            {
              from: 'primevue/tablist',
              imports: ['TabListPassThroughOptions', 'TabListProps'],
              type: true,
            },
            {
              from: 'primevue/tabpanel',
              imports: ['TabPanelPassThroughOptions', 'TabPanelProps'],
              type: true,
            },
            {
              from: 'primevue/tabpanels',
              imports: ['TabPanelsPassThroughOptions', 'TabPanelsProps'],
              type: true,
            },
            {
              from: 'primevue/tabs',
              imports: ['TabsPassThroughOptions', 'TabsProps'],
              type: true,
            },
            {
              from: 'primevue/tag',
              imports: ['TagPassThroughOptions', 'TagProps'],
              type: true,
            },
            {
              from: 'primevue/textarea',
              imports: ['TextareaPassThroughOptions', 'TextareaProps'],
              type: true,
            },
            {
              from: 'primevue/timeline',
              imports: ['TimelinePassThroughOptions', 'TimelineProps'],
              type: true,
            },
            {
              from: 'primevue/toast',
              imports: ['ToastPassThroughOptions', 'ToastProps'],
              type: true,
            },
            {
              from: 'primevue/togglebutton',
              imports: ['ToggleButtonPassThroughOptions', 'ToggleButtonProps'],
              type: true,
            },
            {
              from: 'primevue/toggleswitch',
              imports: ['ToggleSwitchPassThroughOptions', 'ToggleSwitchProps'],
              type: true,
            },
            {
              from: 'primevue/toolbar',
              imports: ['ToolbarPassThroughOptions', 'ToolbarProps'],
              type: true,
            },
            {
              from: 'primevue/tree',
              imports: ['TreePassThroughOptions', 'TreeProps'],
              type: true,
            },
            {
              from: '@/modules/menus/types/app-navigation-type',
              imports: ['AppNavigationType'],
              type: true,
            },
          ],
        },
        vueComponents: {
          dirs: ['resources/modules/**'],
          resolvers: [
            IconsResolver({
              enabledCollections: ['heroicons'],
              prefix: false,
            }),
            PrimeVueResolver(),
          ],
        },
      }),
    ],
    resolve: {
      alias: {
        '@public': path.resolve(__dirname, './public'),
      },
    },
    server: {
      https: {
        cert: readFileSync(`${certPath}.cert`),
        key: readFileSync(`${certPath}.key`),
      },
      watch: {
        // Ignore directories that slow down Vite and cause 'file watchers limit' errors.
        ignored: [
          '**/bootstrap/**',
          '**/database/**',
          '**/storage/**',
          '**/tests/**',
          '**/vendor/**',
        ],
      },
    },
  };
});
