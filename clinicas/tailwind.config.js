import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Outfit', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Clinic Professional Palette
                gray: {
                    50: '#f8fafc',  // Slate 50
                    100: '#f1f5f9', // Slate 100
                    200: '#e2e8f0', // Slate 200
                    300: '#cbd5e1', // Slate 300
                    400: '#94a3b8', // Slate 400
                    500: '#64748b', // Slate 500
                    600: '#475569', // Slate 600
                    700: '#334155', // Slate 700
                    800: '#1e293b', // Slate 800
                    900: '#0f172a', // Slate 900
                    950: '#020617', // Slate 950
                },
                // Primary Brand Color (Medical Blue) replacing Indigo
                indigo: {
                    50: '#f0f9ff',  // Sky 50
                    100: '#e0f2fe', // Sky 100
                    200: '#bae6fd', // Sky 200
                    300: '#7dd3fc', // Sky 300
                    400: '#38bdf8', // Sky 400
                    500: '#0ea5e9', // Sky 500
                    600: '#0284c7', // Sky 600 (Primary Action)
                    700: '#0369a1', // Sky 700
                    800: '#075985', // Sky 800
                    900: '#0c4a6e', // Sky 900
                    950: '#082f49', // Sky 950
                },
            },
            borderRadius: {
                 DEFAULT: '0.375rem',
                 'md': '0.5rem', // Softer corners for inputs
                 'lg': '0.75rem', // Softer corners for cards
            },
        },
    },

    plugins: [forms],
};
