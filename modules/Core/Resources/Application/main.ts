import type { VueHeadClient } from '@unhead/vue/types';
import type { Method } from 'hybridly';
import type { App } from 'vue';
import ui from '@nuxt/ui/vue-plugin';
import { createHead } from '@unhead/vue/client';
import { router } from 'hybridly';

import { initializeHybridly } from 'virtual:hybridly/config';
import './tailwind.css';

initializeHybridly({
  enhanceVue: (vue: App<Element>): void => {
    const head: VueHeadClient = createHead();

    head.push({
      titleTemplate: (title?: string): string => title ? `Sassfolding - ${title}` : 'Sassfolding',
    });

    vue.use(head)
      .use(ui)
      .directive('nuxt-ui-link', {
        mounted() {
          for (const link of document.getElementsByTagName('a') as unknown as HTMLAnchorElement[]) {
            if (!link.dataset.slot) {
              continue;
            }

            link.addEventListener('click', (event) => {
              event.preventDefault();

              const link = event.currentTarget as HTMLAnchorElement;
              const method = link.dataset.method as Method || 'GET';

              router.navigate({
                method,
                preserveState: (method !== 'GET'),
                url: link.href,
              });
            });
          }
        },
      });
  },
}).then();
