import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/admin/js/**/*.js',
        './resources/**/*.vue',
        './resources/admin/js/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: '#4F45E4',
                    50: '#F5F4FE',
                    100: '#E8E7FD',
                    200: '#D2CFFA',
                    300: '#BBB7F8',
                    400: '#A59FF5',
                    500: '#4F45E4',
                    600: '#3F37B7',
                    700: '#2F298A',
                    800: '#1F1C5C',
                    900: '#0F0E2E',
                }
            }
        },
    },
    plugins: [],
};
