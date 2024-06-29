import hybridly from 'hybridly/vite';
import IconsResolver from 'unplugin-icons/resolver';
import { PrimeVueResolver } from 'unplugin-primevue-resolver';
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
          PrimeVueResolver({ prefix: 'PrimeVue' }),
        ],
      },
    }),
  ],
});
