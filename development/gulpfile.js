// --------------------------------------------------------------------
// Plugins
// --------------------------------------------------------------------

var gulp            = require("gulp"),
    sass            = require("gulp-sass"),
    concat          = require("gulp-concat"),
    watch           = require("gulp-watch"),
    plumber         = require("gulp-plumber"),
    minify_css      = require("gulp-minify-css"),
    uglify          = require("gulp-uglify"),
    sourcemaps      = require("gulp-sourcemaps"),
    notify          = require("gulp-notify"),
    imagemin        = require("gulp-imagemin"),
    gutil           = require("gulp-util"),
    postcss         = require("gulp-postcss"),
    autoprefixer    = require("autoprefixer"),
    pngquant        = require("imagemin-pngquant"),
    browserSync     = require("browser-sync"),
    watchify        = require('watchify');

// --------------------------------------------------------------------
// Settings
// --------------------------------------------------------------------

var src = {
    sass: "sass/**/*.scss",
    js: "js/**/*.js",
    img: "img/*",
    third_party: {
        css: [
            'node_modules/bootstrap/dist/css/bootstrap.min.css',
            'node_modules/font-awesome/css/font-awesome.min.css',
            'node_modules/devicons/css/devicons.min.css',
        ],
        js: [
            'node_modules/jquery/dist/jquery.min.js',
            'node_modules/bootstrap/dist/js/bootstrap.min.js'
        ],
        js_map: [
            'node_modules/jquery/dist/jquery.min.map'
        ],
        fonts: [
            'node_modules/font-awesome/fonts/FontAwesome.otf',
            'node_modules/bootstrap/dist/fonts/glyphicons-halflings-regular.eot',
            'node_modules/bootstrap/dist/fonts/glyphicons-halflings-regular.svg',
            'node_modules/bootstrap/dist/fonts/glyphicons-halflings-regular.ttf',
            'node_modules/bootstrap/dist/fonts/glyphicons-halflings-regular.woff',
            'node_modules/bootstrap/dist/fonts/glyphicons-halflings-regular.woff2',
            'node_modules/font-awesome/fonts/fontawesome-webfont.eot',
            'node_modules/font-awesome/fonts/fontawesome-webfont.svg',
            'node_modules/font-awesome/fonts/fontawesome-webfont.ttf',
            'node_modules/font-awesome/fonts/fontawesome-webfont.woff',
            'node_modules/font-awesome/fonts/fontawesome-webfont.woff2',
            'node_modules/devicons/fonts/devicons.eot',
            'node_modules/devicons/fonts/devicons.svg',
            'node_modules/devicons/fonts/devicons.ttf',
            'node_modules/devicons/fonts/devicons.woff',
        ]
    }
};

var output = {
    js: "../public/js",
    css: "../public/css",
    img: "../public/img/",
    fonts: '../public//fonts',
    html: "../app/views/**/*.volt",
    min_css: 'app.min.css',
    min_js: 'app.min.js'
};

// --------------------------------------------------------------------
// Error Handler
// --------------------------------------------------------------------

var onError = function(err) {
    console.log(err);
    this.emit('end');
};

// --------------------------------------------------------------------
// Task: Sass
// --------------------------------------------------------------------

gulp.task('sass', function() {

    return gulp.src([src.sass, "!sass/squeeze.scss"])
        .pipe(plumber({
            errorHandler: onError
        }))
        .pipe(sass())
        .pipe(postcss([ autoprefixer({ browsers: ['last 2 versions'] }) ]))
        .pipe(concat(output.min_css))
        .pipe(gulp.dest(output.css))
        .pipe(minify_css())
        .pipe(sourcemaps.init())
        .pipe(sourcemaps.write())
        .pipe(gulp.dest(output.css))
        .pipe(browserSync.reload({stream: true}));
});

gulp.task('sass_squeeze', function() {

    return gulp.src(["sass/squeeze.scss", src.third_party.css[0]])
        .pipe(plumber({
            errorHandler: onError
        }))
        .pipe(sass())
        .pipe(postcss([ autoprefixer({ browsers: ['last 2 versions'] }) ]))
        .pipe(concat("squeeze.min.css"))
        .pipe(gulp.dest(output.css))
        .pipe(minify_css())
        .pipe(gulp.dest(output.css))
        .pipe(browserSync.reload({stream: true}));
});


// --------------------------------------------------------------------
// Task: JS
// --------------------------------------------------------------------

gulp.task('js', function() {

    return gulp.src(src.js)
        .pipe(plumber({
            errorHandler: onError
        }))
        .pipe(uglify())
        .pipe(concat(output.min_js))
        .pipe(sourcemaps.init())
        .pipe(sourcemaps.write())
        .pipe(gulp.dest(output.js))
        .pipe(browserSync.reload({stream: true}));

});


// --------------------------------------------------------------------
// Task: Image
// --------------------------------------------------------------------

gulp.task('img', function() {

    return gulp.src(src.img)
        .pipe(imagemin({
            progressive: true,
            svgoPlugins: [{removeViewBox: false}],
            use: [pngquant()]
        }))
        .pipe(gulp.dest(output.img));

});


// --------------------------------------------------------------------
// Task: Third Party
// --------------------------------------------------------------------

gulp.task('third_party', function () {

    // These are already minified
    gulp.src(src.third_party.css)
        .pipe(concat('dependencies.min.css'))
        .pipe(gulp.dest(output.css));

    // These are already minified
    gulp.src(src.third_party.js)
        .pipe(concat('dependencies.min.js'))
        .pipe(gulp.dest(output.js));

    // Copy any map files
    gulp.src(src.third_party.js_map)
        .pipe(gulp.dest(output.js));

    // These are plain files
    gulp.src(src.third_party.fonts)
        .pipe(gulp.dest(output.fonts));

});


// --------------------------------------------------------------------
// Task: Watch
// --------------------------------------------------------------------

gulp.task('browser', function() {
    gutil.log(gutil.colors.green('Loading Gulp Browser'), '');
    browserSync.init({
        proxy: 'projects/jream.com',
    });
    gulp.watch(src.js, ['js']);
    gulp.watch(src.sass, ['sass']);
    gulp.watch(src.img, ['img']);
    gulp.watch(output.html).on('change', browserSync.reload);
});

// --------------------------------------------------------------------
// Task: Browser
// --------------------------------------------------------------------
gulp.task('watch', function() {
    gutil.log(gutil.colors.green('Loading Gulp Watch'), '');
    gulp.watch(src.js, ['js']);
    gulp.watch(src.sass, ['sass']);
    gulp.watch(src.img, ['img']);
    gulp.watch(output.html).on('change', browserSync.reload);
});

// --------------------------------------------------------------------
// Task: Default
// --------------------------------------------------------------------

gulp.task('default', ['watch', 'sass', 'sass_squeeze', 'img', 'js']);
