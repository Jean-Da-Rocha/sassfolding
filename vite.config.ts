import { readFileSync } from 'node:fs';
import path from 'node:path';
import process from 'node:process';
import ui from '@nuxt/ui/vite';
import hybridly from 'hybridly/vite';
import IconsResolver from 'unplugin-icons/resolver';
import { defineConfig, loadEnv } from 'vite';

export default defineConfig(({ command, mode }) => {
  const env = loadEnv(mode, process.cwd());
  const composeProjectName = env.VITE_APP_NAME;
  const certPath = `/certs/${composeProjectName}`;

  // Only configure HTTPS for dev server when certificates are available
  const httpsConfig = command === 'serve' && composeProjectName
    ? {
        cert: readFileSync(`${certPath}.cert`),
        key: readFileSync(`${certPath}.key`),
      }
    : undefined;

  return {
    build: {
      sourcemap: false,
    },
    plugins: [
      ui({
        autoImport: {
          dirs: ['modules/**'],
          dts: '.hybridly/auto-import.d.ts',
          imports: [
            {
              from: 'hybridly/vue',
              imports: ['router', 'route', 'useRoute', 'useForm', 'useProperty', 'useDialog'],
            },
            {
              from: '@unhead/vue',
              imports: ['useHead', 'useSeoMeta'],
            },
            {
              from: 'hybridly',
              imports: ['NavigationResponse', 'RouteName', 'Method'],
              type: true,
            },
            {
              from: 'modules/Menus/Types/app-navigation-type',
              imports: ['AppNavigationType'],
              type: true,
            },
          ],
        },
        components: {
          dirs: ['modules/**'],
          dts: '.hybridly/components.d.ts',
          resolvers: [
            IconsResolver({
              enabledCollections: ['heroicons'],
              prefix: false,
            }),
          ],
        },
        router: false,
      }),
      hybridly({
        autoImports: false,
        vueComponents: false,
      }),
    ],
    resolve: {
      alias: {
        '@public': path.resolve(__dirname, './public'),
      },
    },
    server: {
      https: httpsConfig,
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
