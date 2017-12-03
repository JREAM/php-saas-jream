const mix = require('laravel-mix');
const LiveReloadPlugin = require('webpack-livereload-plugin');
const CleanWebpackPlugin = require('clean-webpack-plugin');

mix.options({
  processCssUrls: false, // Process/optimize relative stylesheet url()'s. Set to false, if you don't want them touched.
  publicPath: 'public'
});

mix.webpackConfig({
  plugins: [
    new LiveReloadPlugin(),
    new CleanWebpackPlugin([
      'public/css',
      'public/js',
      'public/vendor',
      'public/fonts',
      'public/images'
    ])
  ]
});

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application, as well as bundling up your JS files.
 |
 */
mix.js('resources/assets/js/app.js', 'public/js')
  .sourceMaps();

mix.sass('resources/assets/sass/app.scss', 'public/css')
  .sourceMaps()
  .options({
    postCss: [
      require('postcss-url')(),
      require('postcss-browser-reporter')(),
      require('postcss-reporter')(),
      require('cssnano')()
    ],
    cleanCss: {
      level: {
        1: {
          specialComments: 'none'
        }
      }
    }
  });

// Images
// -----------------------------------------------------------------------
mix.copyDirectory('./resources/images', './public/images', false);

// Vendor CSS
// -----------------------------------------------------------------------
mix.copy('node_modules/jquery/dist/jquery.min.js', 'public/vendor/jquery.min.js', false)
  .copy('resources/vendor/modernizr-custom.js', 'public/vendor/modernizr-custom.js', false)
  .copy('resources/vendor/notify.js', 'public/vendor/notify.js')
  .copy('node_modules/moment/min/moment-with-locales.min.js', 'public/vendor/moment.min.js', false)
  .copy('node_modules/jquery-expander/jquery.expander.min.js', 'public/vendor/jquery.expander.min.js', false);

mix.js('node_modules/waypoints/lib/jquery.waypoints.min.js', 'public/vendor/jquery.waypoints.min.js', false);

// mix.js("node_modules/bootstrap-validator/dist/validator.min.js", "public/vendor/validator.min.js", false);

// Vendor JS
// -----------------------------------------------------------------------

// Bootstrap Fonts Added (There is a variable in the app.scss to set the path)
mix.copyDirectory('node_modules/bootstrap-sass/assets/fonts/bootstrap', 'public/fonts', false);

// Font Awesome
mix.copyDirectory('node_modules/font-awesome/fonts', 'public/fonts', false);
mix.copyDirectory('node_modules/devicons/fonts', 'public/fonts', false);
mix.copyDirectory('resources/vendor/fonts', 'public/fonts', false);

mix.copy('node_modules/bootstrap-sass/assets/javascripts/bootstrap.min.js', 'public/vendor/bootstrap');

// Flow Player (For Videos on JREAM.com Domain)
mix.copyDirectory('resources/vendor/flowplayer', 'public/vendor/flowplayer', false);


// Font CSS Styles merged
mix.styles([
  'node_modules/font-awesome/css/font-awesome.min.css',
  'node_modules/devicons/css/devicons.min.css',
  'resources/vendor/typicons.css',
  // This is available in my local, just not using now
  // "resources/vendor/material-design-icons.css",
  'resources/vendor/et-line.scss'
], 'public/vendor/fonts.css');
