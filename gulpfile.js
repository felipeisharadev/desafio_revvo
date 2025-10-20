// Importa as dependências
const { src, dest, watch, series, parallel } = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const browserSync = require('browser-sync').create();

const paths = {
    scss: 'assets/sass/**/*.scss',
    scssEntry: 'assets/sass/styles.scss', 
    cssDest: 'public/assets/css/'
};


function style() {
    return src(paths.scssEntry)
        .pipe(sass({ outputStyle: 'compressed' }).on('error', sass.logError))
        .pipe(postcss([autoprefixer()]))
        .pipe(dest(paths.cssDest))
        .pipe(browserSync.stream());
}

function watchFiles() {
    watch(paths.scss, style); 
    watch('**/*.php').on('change', browserSync.reload);
}

function serve() {
    browserSync.init({
        proxy: "localhost:8000", 
        port: 3000 
    });
}

exports.style = style;
exports.watchFiles = watchFiles;
exports.serve = serve;

exports.default = series(style, parallel(serve, watchFiles));
