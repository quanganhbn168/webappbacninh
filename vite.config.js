import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/css/frontend.css',
        'resources/css/filament/admin/theme.css',
        'resources/css/managed-content.css',
        'resources/js/frontend.js',
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
});
