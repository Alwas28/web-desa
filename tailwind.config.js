import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    safelist: [
        'bg-brand-50','bg-brand-100','bg-brand-500','bg-brand-600','bg-brand-700',
        'text-brand-600','text-brand-700',
        'dark:bg-brand-500/10','dark:text-brand-100',
        'border-brand-600',
        'hover:bg-brand-700',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    50:  'rgb(var(--brand-50) / <alpha-value>)',
                    100: 'rgb(var(--brand-100) / <alpha-value>)',
                    500: 'rgb(var(--brand-500) / <alpha-value>)',
                    600: 'rgb(var(--brand-600) / <alpha-value>)',
                    700: 'rgb(var(--brand-700) / <alpha-value>)',
                },
            },
        },
    },
    plugins: [forms],
};
