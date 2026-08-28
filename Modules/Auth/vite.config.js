import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { dirname } from 'path';
import { fileURLToPath } from 'url';

const __dirname = dirname(fileURLToPath(import.meta.url));

export default defineConfig({
    build: {
        outDir: '../../public/build-auth',
        emptyOutDir: true,
        manifest: true,
    },
    plugins: [
        laravel({
            publicDirectory: '../../public',
            buildDirectory: 'build-auth',
            input: [
                __dirname + '/resources/assets/js/app.js'
            ],
            refresh: true,
        }),
    ],
});
