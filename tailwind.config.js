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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'aat-blue': '#003366',
                'aat-blue-light': '#004c99',
                'aat-yellow': '#FFCC00',
                'aat-yellow-dark': '#e6b800',
                'aat-gray': '#F8FAFC',
                'aat-text': '#1F2937',
            }
        },
    },

    plugins: [forms],
};