import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/css/app.css',
        'resources/css/frontend/style.css',
        'resources/css/frontend/navigation.css',
        'resources/css/frontend/content-pages.css',
        'resources/css/frontend/knowledge.css',
        'resources/css/frontend/legal-pages.css',
        'resources/css/frontend/operation-service-detail.css',
        'resources/css/frontend/project-detail.css',
        'resources/css/frontend/projects.css',
        'resources/css/frontend/theme-detail.css',
        'resources/css/frontend/theme-library.css',
        'resources/css/frontend/website-service-detail.css',
        'resources/css/frontend/website-service.css',
        'resources/js/app-user.js',
      ],
      refresh: true,
    }),
    tailwindcss(),
  ],
});
