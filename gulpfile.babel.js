const PROD = 'TRUE';

const gulp = require('gulp');
const gulpif = require('gulp-if');
const $ = require('gulp-load-plugins')();
const webpack = require('webpack-stream');
const UglifyJsPlugin = require('uglify-js-plugin');
const browserSync = require('browser-sync').create();

/**
 * Return path to config files & folders
 * @type {[object]}
 */
const CONFIG = require('./gulp-config.js');

var onError = function(err) {
	$.notify({
		title: 'Gulp Task Error',
		message: 'Check the console.'
	}).write(err);
	console.log(err.toString());
	this.emit('end');
};

/*
|--------------------------------------------------------------------------
| Styles(css) SCSS => CSS, write source maps & autoprefix
|--------------------------------------------------------------------------
*/
gulp.task('styles', () => {
	return gulp.src(CONFIG.routes.styles.scss)
		.pipe($.plumber({
			errorHandler: onError
		}))
		.pipe($.changed(CONFIG.routes.styles.css, {
			extension: '.css'
		}))
		.pipe($.sourcemaps.init())
		.pipe($.sass({
			outputStyle: 'compressed'
		}))
		.pipe(gulpif(PROD == 'TRUE', $.autoprefixer('last 3 versions', 'ie >= 10')))
		.pipe(gulpif(PROD == 'TRUE', $.sourcemaps.write('./maps')))
		.pipe(gulp.dest(CONFIG.routes.styles.css))
		.pipe(browserSync.stream());
});

/*
|--------------------------------------------------------------------------
| Scripts (js) ES6 => ES5, minify and concat into a single file
|--------------------------------------------------------------------------
*/
gulp.task('scripts', () => {
	return gulp.src(CONFIG.routes.scripts.js)
		.pipe($.plumber({
			errorHandle: onError
		}))
		.pipe($.changed(CONFIG.routes.scripts.jsmin, {
			extension: '.js'
		}))
		.pipe(webpack({
			devtool: 'source-map',
			output: {
				filename: 'main.js',
			},
			plugins: PROD == 'TRUE' ? [new UglifyJsPlugin()] : '',
			module: {
				loaders: [{
					test: /\.js$/,
					exclude: /(node_modules|bower_components)/,
					loader: 'babel-loader',
					query: {
						presets: ['es2015']
					}
				}]
			},
		}))
		.pipe(gulp.dest(CONFIG.routes.scripts.jsmin))
		.pipe(browserSync.stream({
			once: true
		}));
});

gulp.task('build', ['styles', 'scripts']);

/*
|--------------------------------------------------------------------------
| Default task
|--------------------------------------------------------------------------
*/
gulp.task('default', () => {
	gulp.start('build');
});
