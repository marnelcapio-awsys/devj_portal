const mix = require('laravel-mix');

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

mix.js('resources/js/app.js', 'public/js')
    .minify('public/js/app.js')
    .postCss('resources/css/app.css', 'public/css', [
        //
    ])
    .minify('public/css/app.css')
    .js('resources/js/employee.js', 'public/js')
    .minify('public/js/employee.js')
    .postCss('resources/css/employee.css', 'public/css', [
        //
    ])
    .minify('public/css/employee.css')

