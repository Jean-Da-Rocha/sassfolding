import type { NavigationType } from '@/modules/menus/types/navigation-type';

export const navigation: Record<string, NavigationType[]> = {
  modules: [
    { initial: 'M', navigationLinkName: 'Module 1' },
    { initial: 'M', navigationLinkName: 'Module 2' },
    { initial: 'M', navigationLinkName: 'Module 3' },
    { initial: 'M', navigationLinkName: 'Module 4' },
  ],
};
