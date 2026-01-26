import type { OptionsConfig, TypedFlatConfigItem } from '@antfu/eslint-config';
import type { Linter } from 'eslint';
import { antfu } from '@antfu/eslint-config';
import eslintPluginBetterTailwindcss from 'eslint-plugin-better-tailwindcss';

const tailwindRules = eslintPluginBetterTailwindcss.configs['recommended-error'].rules as Linter.RulesRecord;

const options = {
  stylistic: {
    semi: true,
  } as OptionsConfig['stylistic'],
  yaml: false,
} satisfies OptionsConfig;

const customRules = {
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
    'style/array-bracket-newline': ['error', { multiline: true }],
    'style/array-element-newline': ['error', 'consistent'],
    'style/brace-style': ['error', '1tbs'],
    'style/object-curly-newline': [
      'error',
      {
        consistent: true,
        multiline: true,
      },
    ],
    'style/object-property-newline': ['error', { allowAllPropertiesOnSameLine: false }],
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
    ...tailwindRules,
  },
  settings: {
    'better-tailwindcss': {
      entryPoint: 'modules/Core/Resources/Application/tailwind.css',
    },
  },
} satisfies TypedFlatConfigItem;

const vueConfig = {
  files: ['**/*.vue'],
  ignores: ['tsconfig.json', 'storage/**'],
  languageOptions: {
    parser: await import('vue-eslint-parser'),
  },
} satisfies TypedFlatConfigItem;

export default antfu(options, customRules, vueConfig);
