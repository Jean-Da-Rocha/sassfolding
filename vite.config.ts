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
              from: 'primevue/message',
              imports: ['MessagePassThroughOptions', 'MessageProps'],
              type: true,
            },
            {
              from: 'primevue/paginator',
              imports: ['PaginatorPassThroughOptions', 'PaginatorProps', 'PageState'],
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
              from: 'primevue/select',
              imports: ['SelectPassThroughOptions', 'SelectProps'],
              type: true,
            },
            {
              from: 'primevue/toggleswitch',
              imports: ['ToggleSwitchPassThroughOptions', 'ToggleSwitchProps'],
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
