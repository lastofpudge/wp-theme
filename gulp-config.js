/* baseDirs: baseDirs for the project */
export const baseDirs = {
    dist: 'assets/dist/',
    src: 'assets/src/',
    assets: 'assets/dist/assets/'
};

/* routes: object that contains the paths */
export const routes = {
    styles: {
        scss: `${baseDirs.src}styles/*.scss`,
        _scss: `${baseDirs.src}styles/_includes/*.scss`,
        css: `${baseDirs.dist}css/`
    },

    scripts: {
        base: `${baseDirs.src}scripts/`,
        js: `${baseDirs.src}scripts/*.js`,
        jsSrc: `${baseDirs.src}scripts/libs/**`,
        jsDist: `${baseDirs.dist}js/libs/`,
        jsmin: `${baseDirs.dist}js/`
    },

    files: {
        html: 'dist/',
        images: `${baseDirs.src}images/*`,
        imgmin: `${baseDirs.dist}files/img/`,
        cssFiles: `${baseDirs.dist}css/*.css`,
        htmlFiles: `${baseDirs.dist}*.html`,
        styleCss: `${baseDirs.dist}css/style.css`
    }
};
