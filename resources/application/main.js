import './tailwind.css';
import Lara from '@/libraries/primevue/presets/lara';
import { createHead } from '@unhead/vue';
import PrimeVue from 'primevue/config';
import { initializeHybridly } from 'virtual:hybridly/config';
initializeHybridly({
    enhanceVue: (vue) => {
        const head = createHead();
        head.push({
            titleTemplate: (title) => title ? `Powerboard - ${title}` : 'Powerboard',
        });
        vue.use(head);
        vue.use(PrimeVue, { pt: Lara, ripple: true, unstyled: true });
    },
}).then();
//# sourceMappingURL=main.js.map