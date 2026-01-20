import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                // Agregar más entradas aquí según el proyecto
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0', // Permitir acceso desde Docker
        port: 5173,
        strictPort: true,
        watch: {
            usePolling: true, // Necesario para Docker en Windows
            ignored: ['**/storage/framework/views/**'],
        },
        hmr: {
            host: 'localhost',
        },
    },
    build: {
        manifest: true,
        outDir: 'public/build',
        rollupOptions: {
            output: {
                manualChunks: undefined,
            },
        },
    },
});
