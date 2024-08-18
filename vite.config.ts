import { PrimeVueResolver } from '@primevue/auto-import-resolver';
import hybridly from 'hybridly/vite';
import IconsResolver from 'unplugin-icons/resolver';
import { HeadlessUiResolver } from 'unplugin-vue-components/resolvers';
import { defineConfig } from 'vite';

export default defineConfig({
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
          IconsResolver({
            enabledCollections: ['heroicons'],
            prefix: false,
          }),
          PrimeVueResolver({ components: { prefix: 'PrimeVue' } }),
          HeadlessUiResolver({ prefix: 'Headless' }),
        ],
      },
    }),
  ],
});
