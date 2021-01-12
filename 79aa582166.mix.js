const mix = require('laravel-mix');


mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]);
    // .browserSync('laravel.test')
    .polyfill({
        enabled: true,
        useBuiltIns: "usage",
        targets: {"ie": 10},
        debug: true
     })
    .version();
    // .browserSync('laravel.test')
    .polyfill({
        enabled: true,
        useBuiltIns: "usage",
        targets: {"ie": 10},
        debug: true
     })
    .version();