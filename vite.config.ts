import { readFileSync } from 'node:fs';
import path from 'node:path';
import process from 'node:process';
import { PrimeVueResolver } from '@primevue/auto-import-resolver';
import hybridly from 'hybridly/vite';
import IconsResolver from 'unplugin-icons/resolver';
import { defineConfig, loadEnv } from 'vite';
import { primeVueVoltUiTypeImports } from './modules/Core/Resources/Components/volt/imports';

export default defineConfig(({ mode }) => {
  const env = loadEnv(mode, process.cwd());
  const composeProjectName = env.VITE_APP_NAME;
  const certPath = `/certs/${composeProjectName}`;

  return {
    build: {
      sourcemap: false,
    },
    plugins: [
      hybridly({
        autoImports: {
          dirs: ['modules/**'],
          imports: [
            {
              from: 'hybridly',
              imports: ['NavigationResponse', 'RouteName'],
              type: true,
            },
            ...primeVueVoltUiTypeImports,
            {
              from: 'modules/Menus/Types/app-navigation-type',
              imports: ['AppNavigationType'],
              type: true,
            },
          ],
        },
        vueComponents: {
          dirs: ['modules/**'],
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
