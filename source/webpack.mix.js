const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');
const autoprefixer = require('autoprefixer');
require('laravel-mix-purgecss');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.ts('resources/assets/vue/src/main.ts', 'public/js')
    .postCss('resources/assets/vue/src/assets/styles/main.css', 'public/css', [
        autoprefixer,
        tailwindcss('./tailwind.config.js')
    ])
    .purgeCss()
    .webpackConfig({
        module: {
          rules: [
            {
                test: /\.tsx?$/,
                loader: "ts-loader",
                exclude: /node_modules/
            }
          ]
        },
        resolve: {
            extensions: ["*", ".js", ".jsx", ".vue", ".ts", ".tsx"],
            alias: {
              '@': __dirname + '/resources/assets/vue/src'
            },
        }
    });
