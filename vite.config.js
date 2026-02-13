import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'public/css/app.css',
                'public/js/app.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        hmr: {
            host: 'localhost',
            port: 5173,
            protocol: 'wss',
        },
        https: true,
        headers: {
            'Access-Control-Allow-Origin': '*',
        },
    },
});
