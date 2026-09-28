import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/scss/bootstrap.scss',
        'resources/css/site.css',
        'resources/js/frontend.js',
        'resources/css/basic.css',
        'resources/js/basic.js',
        'resources/css/filament/admin/theme.css',
        'resources/css/app.css',
        'resources/css/landing/purehome.css',
        'resources/js/landing/purehome.js',
        'resources/css/landing/freshair.css',
        'resources/js/landing/freshair.js',
        'resources/js/app-user.js',
      ],
      refresh: true,
    }),
    tailwindcss(),
  ],
  css: {
    preprocessorOptions: {
      // Bootstrap 5.3 still uses Sass @import and global functions.
      scss: { quietDeps: true, silenceDeprecations: ['import', 'global-builtin', 'color-functions', 'if-function'] },
    },
  },
});
