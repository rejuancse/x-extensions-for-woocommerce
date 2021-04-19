const gulp = require('gulp');
const sass = require('gulp-sass');
const browserSync = require('browser-sync').create();
const concat = require('gulp-concat');
const cssnano = require('gulp-cssnano');
const uglify = require('gulp-uglify');
const pipeline = require('readable-stream').pipeline;

function style() {
    // 1. where is my sass file
    return gulp.src('./assets/scss/**/*.scss')
    // 2. pass that file through sass compiler
     .pipe(sass().on('error', sass.logError))
     // 3. where do I save the compiler css
     .pipe(gulp.dest('./assets/css'))
     // 4. stream changes to all browser.
     pipe(browserSync.stream());
}

function watch() {
    browserSync.init({
        server: {
            baseDir: './'
        }
    });
    gulp.watch('./assets/css/**/*.scss', style);
    gulp.watch('./*.html').on('change', browserSync.reload);
    gulp.watch('./assets/js/**/*.js').on('change', browserSync.reload);
    // gulp.watch('./js/**/*.php').on('change', browserSync.reload);
}

exports.style = style;
exports.watch = watch;


