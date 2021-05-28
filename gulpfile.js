const { src, dest, watch, series } = require('gulp');
const sass = require('gulp-sass');
const postcss = require('gulp-postcss');
const cssnano = require('cssnano');
const terser = require('gulp-terser');
const browsersync = require('browser-sync').create();

// Sass Task
function scssTask(){
  return src(['assets/scss/xwoo-front.scss', 'assets/scss/xwoo-admin.scss'], { sourcemaps: true })
    .pipe(sass())
    .pipe(postcss([cssnano()]))
    .pipe(dest('assets/css', { sourcemaps: '.' }));
}

// JavaScript Task
function jsTask(){
  return src(['assets/js/xwoo-admin.js', 'assets/js/xwoo-front.js'], { sourcemaps: true })
    .pipe(terser())
    .pipe(dest('assets/dist/js', { sourcemaps: '.' }));
}

// Browsersync Tasks
function browsersyncServe(cb){
    browsersync.init({
        server: {
            baseDir: '.'
        }
    });
    cb();
}

function browsersyncReload(cb){
    browsersync.reload();
    cb();
}

// Watch Task
function watchTask(){
    // watch('*.html', browsersyncReload);
    watch(['assets/scss/**/*.scss', 'assets/js/**/*.js'], series(scssTask, jsTask, browsersyncReload));
}

// Default Gulp task
exports.default = series(
    scssTask,
    jsTask,
    browsersyncServe,
    watchTask
);