/**
 * WordPress Theme-specific Gulp file.
 *
 * Instructions
 *
 * In command line, cd into the project directory and run these commands:
 * npm init
 * sudo npm install --save-dev gulp gulp-util gulp-load-plugins browser-sync
 * sudo npm install --save-dev gulp-sourcemaps gulp-autoprefixer gulp-line-ending-corrector gulp-filter gulp-merge-media-queries gulp-cssnano gulp-sass gulp-concat gulp-uglify gulp-notify gulp-imagemin gulp-rename gulp-wp-pot
 *
 * Implements:
 * 			1. Live reloads browser with BrowserSync.
 * 			2. CSS: Sass to CSS conversion, error catching, Autoprixing, Sourcemaps,
 * 				 CSS minification, and Merge Media Queries.
 * 			3. JS: Concatenates & uglifies Vendor and Custom JS files.
 * 			4. Images: Minifies PNG, JPEG, GIF and SVG images.
 * 			5. Watches files for changes in CSS or JS.
 * 			6. Watches files for changes in PHP.
 * 			7. Corrects the line endings.
 *      8. InjectCSS instead of browser page reload.
 *      9. Generates .pot file for i18n and l10n.
 *
 * @since 1.0.0
 * @author Ahmad Awais (@mrahmadawais) and Chris Wilcoxson (@slushman)
 */

/**
 * Project Configuration for gulp tasks.
 */
// Project related.
var project 					= 'Anchorhead'; // Project Name.
var projectURL 					= 'anchorhead.dev'; // Project URL. Could be something like localhost:8888.
var productURL 					= './'; // Theme/Plugin URL. Leave as is.

// Translation related.
var text_domain 				= 'anchorhead';
var destFile 					= 'anchorhead.pot';
var package 					= 'Anchorhead';
var bugReport 					= 'http://www.slushman.com';
var lastTranslator 				= 'Chris Wilcoxson <chris@slushman.com>';
var team 						= '';
var translatePath 				= './assets/languages'

// Public styles
var publicSRC 					= './src/sass/anchorhead-public.scss'; // Path to main .scss file.
var publicDestination 			= './assets/css/'; // Path to place the compiled CSS file.

// Admin styles
var adminStyleSRC 				= './src/sass/anchorhead-admin.scss'; // Path to admin.scss file.
var adminStyleDestination 		= './assets/css/'; // Path to place the compiled CSS file.

// JS Public
var jsPublicSRC          		= './src/js/public/*.js'; // Path to JS public scripts folder.
var jsPublicDestination  		= './assets/js/'; // Path to place the compiled JS public scripts file.
var jsPublicFile 				= 'anchorhead-public'; // Compiled JS public file name

// JS Admin
var jsAdminSRC 					= './src/js/admin/*.js'; // Path to JS admin scripts folder.
var jsAdminDestination 			= './assets/js/'; // Path to place the compiled JS admin scripts file.
var jsAdminFile 				= 'anchorhead-admin'; // Compiled JS admin file name.

// JS Customizer
var jsCustomizerSRC 			= './src/js/customizer/*.js'; // Path to JS customizer scripts folder.
var jsCustomizerDestination 	= './assets/js/'; // Path to place the compiled JS customizer scripts file.
var jsCustomizerFile 			= 'anchorhead-customizer'; // Compiled JS customizer file name.

// JS Customizer
var jsCustControlsSRC 			= './src/js/customizer-controls/*.js'; // Path to JS customizer scripts folder.
var jsCustControlsDestination 	= './assets/js/'; // Path to place the compiled JS customizer scripts file.
var jsCustControlsFile 			= 'anchorhead-customizer-controls'; // Compiled JS customizer file name.

// Watch files paths.
var styleWatchFiles 			= './src/sass/*.scss'; // Path to all *.scss files inside css folder and inside them.
var publicJSWatchFiles 			= './src/js/public/*.js'; // Path to all public JS files.
var adminJSWatchFiles 			= './src/js/admin/*.js'; // Path to all admin JS files.
var customizerJSWatchFiles 		= './src/js/customizer/*.js'; // Path to all customizer JS files.
var custcontrolsJSWatchFiles 	= './src/js/customizer-controls/*.js'; // Path to all customizer JS files.
var projectPHPWatchFiles 		= './*.php'; // Path to all PHP files.

/**
* Load gulp plugins and assing them semantic names.
*/
var gulp 						= require('gulp'); // Gulp of-course
var plugins 					= require('gulp-load-plugins')();
var browserSync 				= require('browser-sync').create(); // Reloads browser and injects CSS.
var reload 						= browserSync.reload; // For manual browser reload.

/**
 * Browsers you care about for autoprefixing.
 */
const AUTOPREFIXER_BROWSERS = [
	'last 2 version',
	'> 1%',
	'ie >= 9',
	'ie_mob >= 10',
	'ff >= 30',
	'chrome >= 34',
	'safari >= 7',
	'opera >= 23',
	'ios >= 7',
	'android >= 4',
	'bb >= 10'
];

/**
 * Live Reloads, CSS injections, Localhost tunneling.
 *
 * @link http://www.browsersync.io/docs/options/
 */
gulp.task( 'browser-sync', function() {
	browserSync.init({
		proxy: projectURL,
		host: projectURL,
		open: 'external',
		injectChanges: true,
		browser: "google chrome"
	});
});

/**
 * Creates style.css.
 */
gulp.task( 'publicStyle', function () {
	gulp.src( publicSRC )
		.pipe( plugins.sourcemaps.init() )
		.pipe( plugins.sass( {
			errLogToConsole: true,
			includePaths: ['./src/sass'],
			outputStyle: 'compact',
			precision: 10
		} ) )
		.on('error', console.error.bind(console))
		.pipe( plugins.sourcemaps.write( { includeContent: false } ) )
		.pipe( plugins.sourcemaps.init( { loadMaps: true } ) )
		.pipe( plugins.autoprefixer( AUTOPREFIXER_BROWSERS ) )
		.pipe( plugins.sourcemaps.write ( './' ) )
		.pipe( plugins.lineEndingCorrector() )
		.pipe( gulp.dest( publicDestination ) )
		.pipe( plugins.filter( '**/*.css' ) ) // Filtering stream to only css files
		.pipe( plugins.mergeMediaQueries( { log: true } ) ) // Merge Media Queries only for final version.

		.pipe( plugins.cssnano())
		.pipe( plugins.lineEndingCorrector() )
		.pipe( gulp.dest( publicDestination ) )

		.pipe( plugins.filter( '**/*.css' ) ) // Filtering stream to only css files
		.pipe( browserSync.stream() ) // Reloads style.css if that is enqueued.
		.pipe( plugins.notify( { message: 'TASK: "publicStyle" Completed! 💯', onLast: true } ) );
});

/**
 * Creates admin.css.
 */
gulp.task( 'adminStyle', function () {
	gulp.src( adminStyleSRC )
		.pipe( plugins.sourcemaps.init() )
		.pipe( plugins.sass( {
			errLogToConsole: true,
			includePaths: ['./sass'],
			outputStyle: 'compact',
			precision: 10
		} ) )
		.on('error', console.error.bind(console))
		.pipe( plugins.sourcemaps.write( { includeContent: false } ) )
		.pipe( plugins.sourcemaps.init( { loadMaps: true } ) )
		.pipe( plugins.autoprefixer( AUTOPREFIXER_BROWSERS ) )
		.pipe( plugins.sourcemaps.write ( './' ) )
		.pipe( plugins.lineEndingCorrector() )
		.pipe( gulp.dest( adminStyleDestination ) )
		.pipe( plugins.filter( '**/*.css' ) ) // Filtering stream to only css files
		.pipe( plugins.mergeMediaQueries( { log: true } ) ) // Merge Media Queries only for final version.

		.pipe( plugins.cssnano())
		.pipe( plugins.lineEndingCorrector() )
		.pipe( gulp.dest( adminStyleDestination ) )

		.pipe( plugins.filter( '**/*.css' ) ) // Filtering stream to only css files
		.pipe( browserSync.stream() ) // Reloads style.css if that is enqueued.
		.pipe( plugins.notify( { message: 'TASK: "adminStyle" Completed! 💯', onLast: true } ) );
});

/**
 * Concatenate and minify public JS scripts.
 */
gulp.task( 'publicJS', function() {
	gulp.src( jsPublicSRC )
		.pipe( plugins.concat( jsPublicFile + '.js' ) )
		.pipe( plugins.lineEndingCorrector() )
		.pipe( gulp.dest( jsPublicDestination ) )
		.pipe( plugins.rename( {
			basename: jsPublicFile,
			suffix: '.min'
		}))
		.pipe( plugins.uglify() )
		.pipe( plugins.lineEndingCorrector() )
		.pipe( gulp.dest( jsPublicDestination ) )
		.pipe( plugins.notify( { message: 'TASK: "publicJS" Completed! 💯', onLast: true } ) );
});

/**
 * Concatenate and minify admin JS scripts.
 */
gulp.task( 'adminJS', function() {
	gulp.src( jsAdminSRC )
		.pipe( plugins.concat( jsAdminFile + '.js' ) )
		.pipe( plugins.lineEndingCorrector() )
		.pipe( gulp.dest( jsAdminDestination ) )
		.pipe( plugins.rename( {
			basename: jsAdminFile,
			suffix: '.min'
		}))
		.pipe( plugins.uglify() )
		.pipe( plugins.lineEndingCorrector() )
		.pipe( gulp.dest( jsAdminDestination ) )
		.pipe( plugins.notify( { message: 'TASK: "adminJS" Completed! 💯', onLast: true } ) );
});

/**
 * Concatenate and minify customizer JS scripts.
 */
gulp.task( 'customizerJS', function() {
	gulp.src( jsCustomizerSRC )
		.pipe( plugins.concat( jsCustomizerFile + '.js' ) )
		.pipe( plugins.lineEndingCorrector() )
		.pipe( gulp.dest( jsCustomizerDestination ) )
		.pipe( plugins.rename( {
			basename: jsCustomizerFile,
			suffix: '.min'
		}))
		.pipe( plugins.uglify() )
		.pipe( plugins.lineEndingCorrector() )
		.pipe( gulp.dest( jsCustomizerDestination ) )
		.pipe( plugins.notify( { message: 'TASK: "customizerJS" Completed! 💯', onLast: true } ) );
});

/**
 * Concatenate and minify customizer control JS scripts.
 */
gulp.task( 'custcontrolsJS', function() {
	gulp.src( jsCustControlsSRC )
		.pipe( plugins.concat( jsCustControlsFile + '.js' ) )
		.pipe( plugins.lineEndingCorrector() )
		.pipe( gulp.dest( jsCustControlsDestination ) )
		.pipe( plugins.rename( {
			basename: jsCustControlsFile,
			suffix: '.min'
		}))
		.pipe( plugins.uglify() )
		.pipe( plugins.lineEndingCorrector() )
		.pipe( gulp.dest( jsCustControlsDestination ) )
		.pipe( plugins.notify( { message: 'TASK: "custcontrolsJS" Completed! 💯', onLast: true } ) );
});

/**
 * WP POT Translation File Generator.
 */
gulp.task( 'translate', function () {
	return gulp.src( projectPHPWatchFiles )
		.pipe( sort() )
		.pipe( plugins.wpPot({
			domain        : text_domain,
			destFile      : destFile,
			package       : package,
			bugReport     : bugReport,
			lastTranslator: lastTranslator,
			team          : team
		}))
		.pipe( gulp.dest(translatePath) )
		.pipe( plugins.notify( { message: 'TASK: "translate" Completed! 💯', onLast: true } ) );
});

/**
 * Watches for file changes and runs specific tasks.
 */
var watchers = ['publicStyle', 'adminStyle', 'publicJS', 'adminJS', 'customizerJS', 'custcontrolsJS', 'browser-sync'];
gulp.task( 'default', watchers, function () {
	gulp.watch( projectPHPWatchFiles, reload ); // Reload on PHP file changes.
	gulp.watch( styleWatchFiles, [ 'publicStyle', 'adminStyle' ] ); // Reload on SCSS file changes.
	gulp.watch( publicJSWatchFiles, [ 'publicJS', reload ] ); // Reload on publicJS file changes.
	gulp.watch( adminJSWatchFiles, [ 'adminJS', reload ] ); // Reload on adminJS file changes.
	gulp.watch( customizerJSWatchFiles, [ 'customizerJS' ] ); // Reload on customizerJS file changes.
	gulp.watch( custcontrolsJSWatchFiles, [ 'custcontrolsJS' ] ); // Reload on customizerJS file changes.
});
