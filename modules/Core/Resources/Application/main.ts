import type { VueHeadClient } from '@unhead/vue/types';
import type { App } from 'vue';
import ui from '@nuxt/ui/vue-plugin';
import { createHead } from '@unhead/vue/client';

import { initializeHybridly } from 'virtual:hybridly/config';
import './tailwind.css';

initializeHybridly({
  enhanceVue: (vue: App<Element>): void => {
    const head: VueHeadClient = createHead();

    head.push({
      titleTemplate: (title?: string): string => title ? `Sassfolding - ${title}` : 'Sassfolding',
    });

    vue.use(head).use(ui);
  },
}).then();
