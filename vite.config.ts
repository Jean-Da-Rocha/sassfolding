import type { Options as AutoImportOptions } from 'unplugin-auto-import/types';
import type { Options as ComponentsOptions } from 'unplugin-vue-components/types';
import type { UserConfig } from 'vite';
import { readFileSync } from 'node:fs';
import path from 'node:path';
import process from 'node:process';
import ui from '@nuxt/ui/vite';
import tailwindcss from '@tailwindcss/vite';
import hybridly from 'hybridly/vite';
import IconsResolver from 'unplugin-icons/resolver';
import { defineConfig, loadEnv } from 'vite';

export default defineConfig(({ command, mode }): UserConfig => {
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

  const autoImportConfig: Partial<AutoImportOptions> = {
    dirs: ['modules/**'],
    dts: '.hybridly/auto-imports.d.ts',
    imports: [
      'vue',
      '@vueuse/core',
      {
        from: 'hybridly/vue',
        imports: [
          'useProperty',
          'setProperty',
          'useRefinements',
          'useTable',
          'useBulkSelect',
          'useProperties',
          'useBackForward',
          'useContext',
          'useForm',
          'useDialog',
          'useHistoryState',
          'usePaginator',
          'registerHook',
          'useRoute',
          'useQueryParameter',
          'useQueryParameters',
        ],
      },
      {
        from: '@unhead/vue',
        imports: ['useHead', 'useSeoMeta'],
      },
      {
        from: 'hybridly',
        imports: ['router', 'route', 'can', 'getRouterContext'],
      },
      {
        from: 'modules/Menus/Types/app-navigation-type',
        imports: ['AppNavigationType'],
        type: true,
      },
    ],
    vueTemplate: true,
  };

  const componentsConfig: Partial<ComponentsOptions> = {
    dirs: ['modules/**'],
    dts: '.hybridly/components.d.ts',
    resolvers: [
      IconsResolver({
        enabledCollections: ['heroicons'],
        prefix: false,
      }),
      // Custom resolver for RouterLink from hybridly
      (componentName: string) => {
        if (componentName === 'RouterLink') {
          return { from: 'hybridly/vue', name: 'RouterLink' };
        }
      },
    ],
  };

  return {
    build: {
      sourcemap: false,
    },
    plugins: [
      ui({
        autoImport: autoImportConfig,
        components: componentsConfig,
        router: false,
      }),
      hybridly({
        autoImports: false,
        vueComponents: false,
      }),
      tailwindcss(),
    ],
    resolve: {
      alias: {
        '@public': path.resolve(__dirname, './public'),
      },
    },
    server: {
      ...(httpsConfig ? { https: httpsConfig } : {}),
      watch: {
        // Ignore directories that slow down Vite and cause 'file watchers limit' errors.
        ignored: [
          '**/bootstrap/**',
          '**/database/**',
          '**/storage/**',
          '**/tests/**',
          '**/vendor/**',
          '**/.pnpm-store/**',
          '**/node_modules/**',
        ],
      },
    },
  };
});
