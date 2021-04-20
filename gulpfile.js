const gulp = require('gulp');
const sass = require('gulp-sass');
const browserSync = require('browser-sync').create();
// const concat = require('gulp-concat');
// const cssnano = require('gulp-cssnano');
// const uglify = require('gulp-uglify');
// const pipeline = require('readable-stream').pipeline;

function style() {
    return gulp.src('./assets/scss/**/*.scss')
     .pipe(sass().on('error', sass.logError))
     .pipe(gulp.dest('./assets/css'))
     pipe(browserSync.stream());
}

function watch() {
    browserSync.init({
        server: {
            baseDir: './'
        }
    });
    gulp.watch('./assets/scss/**/*.scss', style);
    gulp.watch('./*.php').on('change', browserSync.reload);
    gulp.watch('./assets/js/**/*.js').on('change', browserSync.reload);
}

exports.style = style;
exports.watch = watch;


