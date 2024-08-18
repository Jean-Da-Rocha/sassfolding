import type { Config } from 'tailwindcss';

import primeui from 'tailwindcss-primeui';
import theme from 'tailwindcss/defaultTheme';

export default {
  content: [
    './resources/**/*.{js,ts,vue,blade.php}',
  ],
  darkMode: 'class',
  plugins: [primeui],
  theme: {
    screens: {
      xs: '320px',
      ...theme.screens,
    },
  },
} satisfies Config;
