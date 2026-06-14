import {
    defineConfig,
    loadEnv
} from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig(({
    mode
}) => {
    const env = loadEnv(mode, process.cwd(), '');
    return {
        plugins: [
            laravel({
                input: [
                    'resources/css/app.css',
                    'resources/css/dashboard.css',
                    'resources/css/watch.css',
                    'resources/js/app.js',
                    'resources/js/dashboard.js',
                    'resources/js/watch.js',
                ],
                refresh: true,
            }),
        ],
        server: {
            host: 'localhost',
        },
    };
});
