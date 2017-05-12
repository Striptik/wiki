'use strict';
var
    gulp = require('gulp'),
    notify = require("gulp-notify"),
    concat = require('gulp-concat'),
    sass = require('gulp-sass'),
    sourcemaps = require('gulp-sourcemaps'),
    autoprefixer = require('gulp-autoprefixer');

var DIR = {
    'src': '.',
    'dest': '..'
};

/**
 * @task styles
 * Compile sass/scss to unique css file
 */
gulp.task('styles', function () {
    gulp.src(DIR.src + '/scss/**/*.+(scss|sass)')
        .pipe(sourcemaps.init())
        .pipe(sass({
            //outputStyle: 'compressed',
            //includePaths: require('node-normalize-scss').includePaths
        }).on('error', notify.onError("Error: <%= error.message %>")))
        .pipe(autoprefixer({
            browsers: ['last 3 versions'],
            cascade: false
        }))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest(DIR.dest + '/css/'));
});


/**
 * @task scripts
 * Compile js scripts to unique js file
 */
gulp.task('scripts', function () {
    gulp.src(DIR.src + '/js/**/*.js')
        .pipe(sourcemaps.init())
        .pipe(concat('bundle.js'))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest(DIR.dest + '/js/'));
});

/**
 * @task watch
 * Compile/watch app OTF (dev)
 */
gulp.task('watch', function () {
    gulp.watch(DIR.src + '/scss/**/*.scss', ['styles']);
    gulp.watch(DIR.src + '/js/**/*.js', ['scripts']);
});

/**
 * @task dist
 * Compile entire app
 */
gulp.task('dist', ['styles', 'scripts'], function () {
    return true;
});

/**
 * @task default
 * Compile/watch app OTF (dev)
 */
gulp.task('default', ['watch'], function () {
    return true;
});
