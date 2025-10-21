const { src, dest, watch, series, parallel } = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const browserSync = require('browser-sync').create();

const entries = [
  'assets/sass/styles.scss',   
  'assets/sass/courses.scss'
];

const paths = {
  scss: 'assets/sass/**/*.scss',
  cssDest: 'public/assets/css/',
  php: '**/*.php'
};

function style() {
  return src(entries, { allowEmpty: true })
    .pipe(sass({ outputStyle: 'compressed' }).on('error', sass.logError))
    .pipe(postcss([autoprefixer()]))
    .pipe(dest(paths.cssDest))
    .pipe(browserSync.stream());
}

function watchFiles() {
  watch(paths.scss, style); 
  watch(paths.php).on('change', browserSync.reload);
}

function serve() {
  browserSync.init({
    proxy: 'localhost:8000', 
    port: 3000
  });
}

// gulpfile.js
const del = require('del');

function cleanCss() {
  return del.deleteAsync(['public/assets/css/*.css']);
}


exports.style = style;
exports.watchFiles = watchFiles;
exports.serve = serve;
exports.default = series(cleanCss, style, parallel(serve, watchFiles));
