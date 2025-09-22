import type { VueHeadClient } from '@unhead/vue/types';
import type { App } from 'vue';
import { createHead } from '@unhead/vue/client';
import PrimeVue from 'primevue/config';
import ConfirmationService from 'primevue/confirmationservice';
import FocusTrap from 'primevue/focustrap';

import { initializeHybridly } from 'virtual:hybridly/config';
import './tailwind.css';

initializeHybridly({
  enhanceVue: (vue: App<Element>): void => {
    const head: VueHeadClient = createHead();

    head.push({
      titleTemplate: (title?: string): string => title ? `Sassfolding - ${title}` : 'Sassfolding',
    });

    vue.use(head)
      .use(PrimeVue, { unstyled: true })
      .use(ConfirmationService)
      .directive('focustrap', FocusTrap);
  },
}).then();
