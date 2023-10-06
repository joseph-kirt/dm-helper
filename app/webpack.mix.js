const mix = require('laravel-mix');
const {CleanWebpackPlugin} = require('clean-webpack-plugin');
require('laravel-mix-polyfill');

mix
    .setPublicPath('public/')
    .webpackConfig({
        plugins: [
            new CleanWebpackPlugin({
                dry: false,
                verbose: false,
                cleanOnceBeforeBuildPatterns: [
                    'public/css/generated',
                    'public/js/generated'
                ]
            })
        ]
    })
    .js('assets/js/main.js', 'public/js/generated')
    .vue()
    .options({
        processCssUrls: false
    })
    .polyfill({
        enabled: true,
        useBuiltIns: false,
        targets: 'defaults'
    });
if (mix.inProduction()) {
    mix.version();
} else {
    mix
        .sourceMaps(false, 'source-map')
        .webpackConfig({
            devtool: 'source-map'
        });
}
