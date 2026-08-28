import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/css/app.css',
        'resources/scss/app-user.scss',
        'resources/js/app-user.js',
      ],
      refresh: true,
    }),
    tailwindcss(),
  ],
  css: {
    scss: {
      additionalData: `@import "./theme.scss";`,
    },
  },
  resolve: {
    alias: {
      $: 'jQuery',
    },
  },
});
