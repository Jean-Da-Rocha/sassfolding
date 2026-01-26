import type { OptionsConfig, TypedFlatConfigItem } from '@antfu/eslint-config';
import { antfu } from '@antfu/eslint-config';
import eslintPluginBetterTailwindcss from 'eslint-plugin-better-tailwindcss';

export default await antfu(
  {
    stylistic: {
      semi: true,
    },
    yaml: false,
  } satisfies OptionsConfig,
  {
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
      'ts/consistent-type-definitions': ['error', 'type'],
      'ts/no-unused-expressions': ['off'],
      'vue/attributes-order': ['error', { alphabetical: true }],
      'vue/max-attributes-per-line': [
        'error',
        {
          multiline: 1,
          singleline: 3,
        },
      ],
      ...eslintPluginBetterTailwindcss.configs['recommended-error'].rules,
    },
    settings: {
      'better-tailwindcss': {
        entryPoint: 'modules/Core/Resources/Application/tailwind.css',
      },
    },
  } satisfies TypedFlatConfigItem,
  {
    files: ['**/*.vue'],
    ignores: ['tsconfig.json', 'storage/**'],
    languageOptions: {
      parser: await import('vue-eslint-parser'),
    },
  } satisfies TypedFlatConfigItem,
);
