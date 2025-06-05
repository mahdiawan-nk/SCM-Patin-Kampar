import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/css/filament/panels/theme.css",
            ],
            refresh: true,
        }),
    ],
    server: {
        hmr: {
            host: "localhost", // atau IP kamu jika remote device
        },
    },
});
