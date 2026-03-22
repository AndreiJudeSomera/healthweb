// import { defineConfig } from "vite";
// import laravel from "laravel-vite-plugin";

// export default defineConfig({
//   plugins: [
//     laravel({
//       input: ["resources/css/app.css", "resources/js/app.js"],
//       refresh: true,
//     }),
//   ],
// });
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 
                    'resources/js/app.js',
                    'resources/js/pages/patients-index.js',
                    'resources/js/components/modals/bind-patient-modal.js',
                    
            ], // Adjust as per your setup
            refresh: true, // Enables live reload in development
        }),
    ],
    server: {
        host: '0.0.0.0', // Bind to all IPs for development
        port: 4000, // Development server port
    },
    build: {
        outDir: 'public/build', // Production assets go here
    },
});