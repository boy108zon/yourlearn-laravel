import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,  // This enables hot reloading
        }),
    ],
    define: {
        // Define APP_URL manually if needed (Optional)
        'process.env.APP_URL': JSON.stringify(process.env.APP_URL || 'http://localhost:8000'),
    },
    resolve: {
        alias: {
            '$': 'jQuery'
        },
    },
});
