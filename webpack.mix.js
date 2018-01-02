const mix = require('laravel-mix');
const LiveReloadPlugin = require('webpack-livereload-plugin');
const CleanWebpackPlugin = require('clean-webpack-plugin');

mix.version();

mix.options({
  processCssUrls: false, // Process/optimize relative stylesheet url()'s. Set to false, if you don't want them touched.
  publicPath: 'public'
});

// Use source maps NOT in production
// Keep sourcemaps in git ignore
if (!mix.inProduction()) {
  mix.sourceMaps();
}

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
mix.js('resources/assets/js/app.js', 'public/js');

mix.sass('resources/assets/sass/app.scss', 'public/css')
  .options({
    postCss: [
      require('postcss-sorting')(),
      require('postcss-image-set-polyfill')(),
      require('postcss-url')(),
      require('postcss-browser-reporter')(),
      require('postcss-reporter')(),
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


// Vendor JS
// -----------------------------------------------------------------------
mix.copy('node_modules/jquery/dist/jquery.min.js', 'public/vendor/jquery.min.js', false)
  .copy('node_modules/jquery/dist/jquery.min.map', 'public/vendor/jquery.min.map', false)
  .copy('resources/vendor/modernizr-custom.js', 'public/vendor/modernizr-custom.js', false)
  .copy('node_modules/moment/min/moment-with-locales.min.js', 'public/vendor/moment.min.js', false)
  .copy('node_modules/jquery-expander/jquery.expander.min.js', 'public/vendor/jquery.expander.min.js', false);

mix.copy('node_modules/sweetalert2/dist/sweetalert2.min.js', 'public/vendor/sweetalert2.min.js', false)
  .copy('node_modules/sweetalert2/dist/sweetalert2.min.css', 'public/vendor/sweetalert2.min.css', false);

mix.js('node_modules/bottlejs/dist/bottle.js', 'public/vendor/bottle.min.js', false);
// mix.js("node_modules/bootstrap-validator/dist/validator.min.js", "public/vendor/validator.min.js", false);


// Vendor
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


// Full API
// mix.js(src, output);
// mix.react(src, output); <-- Identical to mix.js(), but registers React Babel compilation.
// mix.ts(src, output); <-- Requires tsconfig.json to exist in the same folder as webpack.mix.js
// mix.extract(vendorLibs);
// mix.sass(src, output);
// mix.standaloneSass('src', output); <-- Faster, but isolated from Webpack.
// mix.fastSass('src', output); <-- Alias for mix.standaloneSass().
// mix.less(src, output);
// mix.stylus(src, output);
// mix.postCss(src, output, [require('postcss-some-plugin')()]);
// mix.browserSync('my-site.dev');
// mix.combine(files, destination);
// mix.babel(files, destination); <-- Identical to mix.combine(), but also includes Babel compilation.
// mix.copy(from, to);
// mix.copyDirectory(fromDir, toDir);
// mix.minify(file);
// mix.sourceMaps(); // Enable sourcemaps
// mix.version(); // Enable versioning.
// mix.disableNotifications();
// mix.setPublicPath('path/to/public');
// mix.setResourceRoot('prefix/for/resource/locators');
// mix.autoload({}); <-- Will be passed to Webpack's ProvidePlugin.
// mix.webpackConfig({}); <-- Override webpack.config.js, without editing the file directly.
// mix.then(function () {}) <-- Will be triggered each time Webpack finishes building.
// mix.options({
//   extractVueStyles: false, // Extract .vue component styling to file, rather than inline.
//   globalVueStyles: file, // Variables file to be imported in every component.
//   processCssUrls: true, // Process/optimize relative stylesheet url()'s. Set to false, if you don't want them touched.
//   purifyCss: false, // Remove unused CSS selectors.
//   uglify: {}, // Uglify-specific options. https://webpack.github.io/docs/list-of-plugins.html#uglifyjsplugin
//   postCss: [] // Post-CSS options: https://github.com/postcss/postcss/blob/master/docs/plugins.md
// });
