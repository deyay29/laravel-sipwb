import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss({
            // Blade bukan file .js/.ts, jadi sumber konten utility class
            // harus didaftarkan eksplisit agar Tailwind memindainya.
            source: {
                entry: 'resources/**/*.{php,blade.php,js}',
            },
        }),
    ],
});
