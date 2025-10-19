// Importa as dependências
const { src, dest, watch, series, parallel } = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const browserSync = require('browser-sync').create();

// ----------------------------------------------------
// CAMINHOS
// ----------------------------------------------------
const paths = {
    // Arquivos SASS: Monitora a raiz de sass E qualquer subpasta
    scss: 'assets/sass/**/*.scss',
    // Caminho para o arquivo principal que importa tudo. O watch continuará monitorando todos.
    // ESTE ARQUIVO DEVE SE CHAMAR EXATAMENTE 'styles.scss' (sem underscore)
    scssEntry: 'assets/sass/styles.scss', 
    // Pasta de destino do CSS compilado
    cssDest: 'public/assets/css/'
};


// ----------------------------------------------------
// 1. TAREFA SASS: Compila, adiciona prefixos e salva
// ----------------------------------------------------
function style() {
    // Usamos o arquivo de entrada (styles.scss) para compilar
    return src(paths.scssEntry)
        // 1. Compilar SASS
        .pipe(sass({ outputStyle: 'compressed' }).on('error', sass.logError))
        // 2. Adicionar prefixos de navegador
        .pipe(postcss([autoprefixer()]))
        // 3. Salvar o CSS final na pasta pública
        .pipe(dest(paths.cssDest))
        // 4. Injetar as mudanças no navegador
        .pipe(browserSync.stream());
}


// ----------------------------------------------------
// 2. TAREFA WATCH: Monitora arquivos
// ----------------------------------------------------
function watchFiles() {
    // Monitora TODOS os arquivos SCSS (incluindo subpastas e raiz)
    watch(paths.scss, style); 
    // Monitora arquivos PHP para recarregar o navegador
    watch('**/*.php').on('change', browserSync.reload);
}


// ----------------------------------------------------
// 3. TAREFA SERVIDOR (BrowserSync)
// ----------------------------------------------------
function serve() {
    browserSync.init({
        // CORRIGIDO: Usa a porta 8000, onde o PHP está rodando
        proxy: "localhost:8000", 
        // Porta do Gulp/BrowserSync
        port: 3000 
    });
}


// ----------------------------------------------------
// EXPORTAR TAREFAS
// ----------------------------------------------------
exports.style = style;
exports.watchFiles = watchFiles;
exports.serve = serve;

// Tarefa padrão (default): Compila, inicia o servidor e o monitoramento
exports.default = series(style, parallel(serve, watchFiles));
