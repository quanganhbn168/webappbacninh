import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/scss/app-admin.scss',
        'resources/scss/app-user.scss',
        'resources/js/app-admin.js',
        'resources/js/app-user.js',
      ],
      refresh: true,
    }),
  ],
  css: {
    preprocessorOptions: {
      scss: {
        additionalData: `@import "./theme.scss";`
      }
    }
  }
});
