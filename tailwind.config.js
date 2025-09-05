const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    safelist: [
        'mb-2',
        'sm:w-3/4',
        'md:text-left',
        'py-8',

        // authのフォーム
        'sm:max-w-md',
        'md:max-w-lg',
        'lg:max-w-xl',
        'xl:max-w-2xl',
        
        // public-show
        'mt-10',
        'max-h-[min(75vh,900px)]',

        // /views/public_site/show.blade.php：リンク集
        'w-4/5',
        'gap-x-6',
        'gap-y-2',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
