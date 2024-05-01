import './tailwind.css';
import Lara from '@/libraries/primevue/presets/lara';
import type { MergeHead, VueHeadClient } from '@unhead/vue';
import { createHead } from '@unhead/vue';
import PrimeVue from 'primevue/config';
import { initializeHybridly } from 'virtual:hybridly/config';
import type { App } from 'vue';

initializeHybridly({
  enhanceVue: (vue: App<Element>): void => {
    const head: VueHeadClient<MergeHead> = createHead();

    head.push({
      titleTemplate: (title?: string): string => title ? `Powerboard - ${title}` : 'Powerboard',
    });

    vue.use(head);
    vue.use(PrimeVue, { pt: Lara, ripple: true, unstyled: true });
  },
}).then();
