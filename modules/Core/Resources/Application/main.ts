import type { VueHeadClient } from '@unhead/vue/types';
import type { App } from 'vue';
import ui from '@nuxt/ui/vue-plugin';
import { createHead } from '@unhead/vue/client';

import { initializeHybridly } from 'virtual:hybridly/config';
import './tailwind.css';

initializeHybridly({
  enhanceVue: (vue: App<Element>): void => {
    const appName = useProperty('app.name');
    const head: VueHeadClient = createHead();

    head.push({
      titleTemplate: (title?: string): string => title ? `${appName.value} - ${title}` : appName.value,
    });

    vue.use(head).use(ui);
  },
}).then();
