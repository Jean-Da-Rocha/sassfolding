import HeroiconsCalendar from '~icons/heroicons/calendar';
import HeroiconsCog8Tooth from '~icons/heroicons/cog-8-tooth';
import HeroiconsFolder from '~icons/heroicons/folder';
import HeroiconsUserGroup from '~icons/heroicons/user-group';

export const navigation = [
  { icon: HeroiconsCalendar, navigationLinkName: 'Calendar' },
  { icon: HeroiconsFolder, navigationLinkName: 'My Files' },
  { icon: HeroiconsUserGroup, navigationLinkName: 'Users', routeName: 'users.index' },
  { icon: HeroiconsCog8Tooth, navigationLinkName: 'Settings' },
] satisfies AppNavigationType[];
