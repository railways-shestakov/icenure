let gulp = require('gulp');
let sass = require('gulp-sass');
let terser = require('gulp-terser');
let autoprefixer = require('gulp-autoprefixer');
let watch = require('gulp-watch');
let browserSync = require('browser-sync').create();
let stripCssComments = require('gulp-strip-css-comments');

gulp.task('style', function () {
    return gulp.src(['sass/*.scss', '!sass/normalize.scss'])
        .pipe(stripCssComments())
        .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
        .pipe(autoprefixer({
            overrideBrowserslist: ['last 3 versions'],
            cascade: false
        }))
        .pipe(gulp.dest('./css/'))
        .pipe(browserSync.stream());
});

gulp.task('js', function () {
    return gulp.src('js/source/**/*.js')
    .pipe(terser({
        mangle: false,
        safari10: true
    }))
    .pipe(gulp.dest('js/min'))
    .pipe(browserSync.stream());
});

gulp.task('watch', function () {
    browserSync.init();
    gulp.watch('sass/**/*.scss', gulp.series('style'));
    gulp.watch("js/source/*.js").on('change', gulp.series('js'));
    gulp.watch("*.php").on('change', browserSync.reload);
    gulp.watch('templates/**/*.twig').on('change', browserSync.reload);
});

gulp.task('default', gulp.parallel('watch', 'style', 'js'));