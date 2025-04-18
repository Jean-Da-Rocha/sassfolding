import { readFileSync } from 'node:fs';
import process from 'node:process';
import { PrimeVueResolver } from '@primevue/auto-import-resolver';
import hybridly from 'hybridly/vite';
import { HeadlessUiResolver } from 'unplugin-vue-components/resolvers';
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
        },
        vueComponents: {
          dirs: ['resources/modules/**'],
          resolvers: [
            PrimeVueResolver({ components: { prefix: 'PrimeVue' } }),
            HeadlessUiResolver({ prefix: 'Headless' }),
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
