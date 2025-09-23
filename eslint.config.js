import { antfu } from '@antfu/eslint-config';
import eslintPluginBetterTailwindcss from 'eslint-plugin-better-tailwindcss';
import eslintParserVue from 'vue-eslint-parser';

export default await antfu({
  plugins: {
    'better-tailwindcss': eslintPluginBetterTailwindcss,
  },
  rules: {
    'curly': ['error', 'multi-line', 'consistent'],
    'import/order': ['off'], // Disable this ESLint rule to avoid conflicts with 'perfectionist/sort-imports'.
    'perfectionist/sort-array-includes': ['error'],
    'perfectionist/sort-imports': ['error'],
    'perfectionist/sort-interfaces': ['error'],
    'perfectionist/sort-objects': ['error'],
    'style/brace-style': ['error', '1tbs'],
    'ts/no-unused-expressions': ['off'],
    'vue/attributes-order': ['error', { alphabetical: true }],
    ...eslintPluginBetterTailwindcss.configs['recommended-error'].rules,
    'better-tailwindcss/no-unregistered-classes': ['error', {
      ignore: ['highlight', 'primary', 'surface'], // PrimeVue Volt UI related.
    }],
  },
  settings: {
    'better-tailwindcss': {
      entryPoint: 'resources/application/tailwind.css',
    },
  },
  stylistic: {
    overrides: { 'ts/consistent-type-definitions': ['error', 'type'] },
    semi: true,
  },
  yaml: false,
}, {
  files: ['**/*.vue'],
  ignores: ['tsconfig.json', 'storage/**'],
  languageOptions: {
    parser: eslintParserVue,
  },
});
