import { readdirSync } from 'node:fs';
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

// One script per tool / payment page, loaded only on that page with @vite('resources/js/tools/<name>.js').
const pageScripts = dir => readdirSync(dir).filter(file => file.endsWith('.js')).map(file => `${dir}/${file}`);

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/scss/site.scss',
        'resources/js/site.js',
        'resources/scss/basic.scss',
        'resources/js/basic.js',
        'resources/css/filament/admin/theme.css',
        'resources/css/landing/purehome.css',
        'resources/js/landing/purehome.js',
        'resources/css/landing/freshair.css',
        'resources/js/landing/freshair.js',
        ...pageScripts('resources/js/tools'),
        ...pageScripts('resources/js/payment'),
        ...pageScripts('resources/js/projects'),
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
