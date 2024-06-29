import './tailwind.css';
import Aura from '@/libraries/primevue/presets/aura';
import type { MergeHead, VueHeadClient } from '@unhead/vue';
import { createHead } from '@unhead/vue';
import PrimeVue from 'primevue/config';
import { initializeHybridly } from 'virtual:hybridly/config';
import type { App } from 'vue';

initializeHybridly({
  enhanceVue: (vue: App<Element>): void => {
    const head: VueHeadClient<MergeHead> = createHead();

    head.push({
      titleTemplate: (title?: string): string => title ? `LVHP Scaffolding - ${title}` : 'LVHP Scaffolding',
    });

    vue.use(head);
    vue.use(PrimeVue, { pt: Aura, ripple: true, unstyled: true });
  },
}).then();
