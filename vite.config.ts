import type { NuxtUIOptions } from '@nuxt/ui/unplugin';
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

  const autoImportConfig = {
    dirs: ['modules/**'],
    dts: '.hybridly/auto-imports.d.ts',
    imports: [
      'vue',
      '@vueuse/core',
      {
        from: 'hybridly/vue',
        imports: [
          'registerHook',
          'setProperty',
          'useBackForward',
          'useBulkSelect',
          'useContext',
          'useDialog',
          'useForm',
          'useHistoryState',
          'usePaginator',
          'useProperties',
          'useProperty',
          'useRefinements',
          'useQueryParameter',
          'useQueryParameters',
          'useRoute',
          'useTable',
        ],
      },
      {
        from: '@unhead/vue',
        imports: ['useHead', 'useSeoMeta'],
      },
      {
        from: 'hybridly',
        imports: ['can', 'getRouterContext', 'route', 'router'],
      },
      {
        from: 'hybridly',
        imports: ['NavigationResponse', 'RouteName'],
        type: true,
      },
      {
        from: 'hybridly/vue',
        imports: [
          'BaseFilterRefinement',
          'BoundFilterRefinement',
          'BulkAction',
          'DateFilterRefinement',
          'FilterRefinement',
          'InlineAction',
          'SortDirection',
          'TimeSuggestion',
          'TimeframeSuggestion',
        ],
        type: true,
      },
      {
        from: '@nuxt/ui',
        imports: ['ColumnDef', 'DropdownMenuItem', 'NavigationMenuItem', 'ToastProps'],
        type: true,
      },
    ],
    vueTemplate: true,
  } satisfies NuxtUIOptions['autoImport'];

  const componentsConfig = {
    dirs: ['modules/**'],
    dts: '.hybridly/components.d.ts',
    resolvers: [
      IconsResolver({
        enabledCollections: ['lucide'],
        prefix: false,
      }),
      // Custom resolver for RouterLink from hybridly
      (componentName: string) => {
        if (componentName === 'RouterLink') {
          return {
            from: 'hybridly/vue',
            name: 'RouterLink',
          };
        }
      },
    ],
  } satisfies NuxtUIOptions['components'];

  const nuxtUIOptions: NuxtUIOptions = {
    autoImport: autoImportConfig,
    components: componentsConfig,
    router: false,
  };

  return {
    build: {
      sourcemap: false,
    },
    plugins: [
      ui(nuxtUIOptions),
      hybridly(),
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
