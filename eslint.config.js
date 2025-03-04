import { antfu } from '@antfu/eslint-config';
// import tailwindcss from 'eslint-plugin-tailwindcss';

export default await antfu({
  plugins: {
    // tailwindcss,
  },
  rules: {
    'curly': ['error', 'multi-line', 'consistent'],
    'import/order': ['off'], // Disable this ESLint rule to avoid conflicts with 'perfectionist/sort-imports'.
    'perfectionist/sort-array-includes': ['error'],
    'perfectionist/sort-imports': ['error'],
    'perfectionist/sort-interfaces': ['error'],
    'perfectionist/sort-objects': ['error'],
    'style/brace-style': ['error', '1tbs'],
    // 'tailwindcss/classnames-order': ['error'],
    // 'tailwindcss/enforces-negative-arbitrary-values': ['error'],
    // 'tailwindcss/enforces-shorthand': ['error'],
    // 'tailwindcss/migration-from-tailwind-2': ['off'],
    // 'tailwindcss/no-custom-classname': ['off'],
    'ts/no-unused-expressions': ['off'],
    'vue/attributes-order': ['error'],
  },
  stylistic: {
    overrides: { 'ts/consistent-type-definitions': ['error', 'type'] },
    semi: true,
  },
  yaml: false,
}, { ignores: ['tsconfig.json', 'storage/**'] });
