import type { NavigationType } from '@/modules/menus/types/navigation-type';

export const navigation: Record<string, NavigationType[]> = {
  modules: [
    { initial: 'M1', navigationLinkName: 'Module 1' },
    { initial: 'M2', navigationLinkName: 'Module 2' },
    { initial: 'M3', navigationLinkName: 'Module 3' },
    { initial: 'M4', navigationLinkName: 'Module 4' },
  ],
};
