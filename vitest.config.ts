import { defineConfig, mergeConfig } from 'vitest/config';
import viteConfig from './vite.config.ts';

// Resolved in `build` mode on purpose: the `serve` branch reads TLS certificates that only
// exist inside the development container.
export default defineConfig(
  mergeConfig(
    viteConfig({
      command: 'build',
      mode: 'test',
    }),
    {
      test: {
        environment: 'node',
        include: ['modules/**/Tests/**/*.test.ts'],
      },
    },
  ),
);
