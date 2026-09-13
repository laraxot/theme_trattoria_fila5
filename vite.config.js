import { defineConfig } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import path from 'node:path';

/**
 * Theme-local build. Run from laravel/Themes/Trattoria with the workspace
 * dependencies installed from laravel/package.json.
 */
export default defineConfig({
    build: {
        outDir: './public',
        emptyOutDir: false,
        manifest: 'manifest.json',
        rollupOptions: {
            input: [
                path.resolve(__dirname, 'resources/css/app.css'),
                path.resolve(__dirname, 'resources/js/app.js'),
            ],
        },
    },
    plugins: [
        laravel({
            publicDirectory: './public',
            buildDirectory: 'themes/Trattoria',
            input: [
                path.resolve(__dirname, 'resources/css/app.css'),
                path.resolve(__dirname, 'resources/js/app.js'),
            ],
            refresh: [...refreshPaths, 'resources/views/**'],
        }),
        tailwindcss(),
    ],
});
