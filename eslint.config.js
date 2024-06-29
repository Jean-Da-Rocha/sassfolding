import { antfu } from '@antfu/eslint-config';
import tailwindcss from 'eslint-plugin-tailwindcss';

export default await antfu({
  plugins: {
    tailwindcss,
  },
  rules: {
    'curly': ['error', 'multi-line', 'consistent'],
    'import/order': ['off'], // Disable this ESLint rule to avoid conflicts with 'perfectionist/sort-imports'
    'perfectionist/sort-array-includes': ['error'],
    'perfectionist/sort-imports': ['error'],
    'perfectionist/sort-interfaces': ['error'],
    'perfectionist/sort-objects': ['error'],
    'perfectionist/sort-vue-attributes': ['error'],
    'tailwindcss/classnames-order': ['error'],
    'tailwindcss/enforces-negative-arbitrary-values': ['error'],
    'tailwindcss/enforces-shorthand': ['error'],
    'tailwindcss/migration-from-tailwind-2': ['off'],
    'tailwindcss/no-custom-classname': ['off'],
    'vue/attributes-order': ['off'],
  },
  stylistic: {
    overrides: { 'ts/consistent-type-definitions': ['error', 'type'] },
    semi: true,
  },
  yaml: false,
}, { ignores: ['resources/libraries/primevue/presets', 'tsconfig.json', 'storage/**'] });
