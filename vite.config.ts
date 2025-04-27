import { readFileSync } from 'node:fs';
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
              from: 'primevue/button',
              imports: ['ButtonPassThroughOptions', 'ButtonProps'],
              type: true,
            },
            {
              from: 'primevue/card',
              imports: ['CardPassThroughOptions', 'CardProps'],
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
              from: 'primevue/fluid',
              imports: ['FluidProps'],
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
              imports: ['PaginatorPassThroughOptions', 'PaginatorProps'],
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
              from: 'primevue/toggleswitch',
              imports: ['ToggleSwitchPassThroughOptions', 'ToggleSwitchProps'],
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
