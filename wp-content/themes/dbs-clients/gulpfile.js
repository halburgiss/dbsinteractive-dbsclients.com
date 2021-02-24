/**
 * Welcome to the Gulpfile here to replace the Gruntfile for speed, effiency
 * maintainability, and did I mention speed?
 *
 * @author Michael Large @ dbs>Interactive
 * @since March 28, 2016
 *
 * @author Harold Bradley III
 * @since Dec. 18, 2016
 *
 * @see https://css-tricks.com/gulp-for-beginners/
 * @see https://github.com/gulpjs/gulp/blob/master/docs/getting-started.md
 * @see https://github.com/gulpjs/gulp/blob/master/docs/API.md
 * @see https://github.com/osscafe/gulp-cheatsheet
 */
(function() {
	//	'use strict'; // 'const' throws errors 2016-10-27

	// DEPENDENCIES
	try {
		require( 'console.table' );
		var Crawler = require( 'simplecrawler' );
		var SpellChecker = require( 'simple-spellchecker' );
		var argv = require( 'yargs' ).argv;
		var autoprefixer = require( 'gulp-autoprefixer' ); // https://github.com/sindresorhus/gulp-autoprefixer
		var babel = require( 'gulp-babel' );
		var bourbon = require( 'node-bourbon' ).includePaths;
		var browserSync = require( 'browser-sync' ).create(); // https://www.browsersync.io/docs/gulp/
		var cheerio = require( 'cheerio' );
		var cleancss = require( 'gulp-clean-css' ); // https://github.com/scniro/gulp-clean-css
		var concat = require( 'gulp-concat' );  // https://github.com/contra/gulp-concat
		var criticalcss = require( 'criticalcss' );
		var del = require( 'del' );
		var es = require( 'event-stream' );
		var exec = require( 'child_process' ).exec;
		var fs = require( 'fs' );
		var gulp = require( 'gulp-help' )( require( 'gulp' ) ); // https://github.com/gulpjs/gulp/ and https://www.npmjs.com/package/gulp-help
		var sassvars = require( 'gulp-sass-variables' ); //https://www.npmjs.com/package/gulp-sass-variables
		var gutil = require( 'gulp-util' );
		var gzip = require( 'gulp-gzip' );
		var ignore = require( 'gulp-ignore' );
		var insert = require( 'gulp-insert' );
		var jshint = require( 'gulp-jshint' );
		var lexing = require( 'lexing' );
		var neat = require( 'node-neat' ).includePaths;
		var path = require( 'path' );
		var plumber = require( 'gulp-plumber' );
		var rename = require( 'gulp-rename' );
		var request = require( 'request' );
		var sass = require( 'gulp-sass' ); // https://www.npmjs.com/package/gulp-sass
		var shell = require( 'gulp-shell' ); // https://www.npmjs.com/package/gulp-shell
		var sourcemaps = require( 'gulp-sourcemaps' ); // https://www.npmjs.com/package/gulp-sourcemaps
		var spawn = require( 'child_process' ).spawn;
		var tmpDir = require( 'os' ).tmpdir();
		var tokenizer = require( 'node-tokenizer' );
		var uglify = require( 'gulp-uglify' ); //https://www.npmjs.com/package/gulp-uglify
		var validator = require( 'html-validator' );
		var vfs = require( 'vinyl-fs' );

	} catch ( error ) {
		console.log( error );

		console.log( '\n\n    *******************************************' );
		console.log( '      [WARN] Missing a slate gulp dependency.' );
		console.log( '             Please run `npm install`.' );
		console.log( '    *******************************************\n' );

		// If we got this far, we at least know gulp is installed:
		require( 'gulp' ).task( 'default', function() {
			console.log( '\n           !! Please make sure node is updated and then run `npm install` !!\n' );
		});
		return;  // Stop here
	}

	process.on( 'SIGINT', function() {
		console.log( 'Caught interrupt signal' );
		clean();
		console.log( 'Finished.' );
		process.exit();
	});

	// LOCATIONS
	const location = {
		'sass'   : 'library/scss',
		'css'    : 'library/css',
		'js'     : {
						'root' : 'library/js',
						'sources' : [
							'library/js/vendors/**/*.js',
							'!library/js/vendors/**/*.min.js',
							'!library/js/vendors/lazyload/*/*',
						],
						'global_sources' : [
							'library/js/global/vendors/**/*.js',
							'!library/js/global/vendors/**/*.min.js',
							'library/js/global/scripts.js'
						],
				   },
		'images' : 'library/images',
		// these are used primarily for to help with CDN usage on relative domain paths in css. 2019-02-20
		'local'  : 'dbsclients.loc',
		'staging': 'staging37.resultsbydesign.com',
		'production' : '',
		// Need this for browser sync to work 2021-02-10
		'local_url' : 'baserepo.loc'
	};


	/**
	 * used for fully qualified domain handling in css files, important for cdn's 2019-02-20
	 * example scss syntax: &__phone:before { background-image: url(  #{$domain}/wp-content/themes/dbswebsite/library/icons/src/phone.svg); }
	 */
	console.log( 'Loading DBS module .sass_env.js, if error due to not found copy to theme from .dbs folder' );
	if ( ! file_exists('./.sass_env.js') ) {
		fs.createReadStream('../../../.dbs/.sass_env.js').pipe(fs.createWriteStream('./.sass_env.js'));
	}
	var sass_env = require( './.sass_env.js' );
	console.log( 'SASS_ENV: ', sass_env.env );

	var domain = location[sass_env.env];
	// Use cdn if it is set.
	if ( sass_env.cdn_domain === 'true' ) {
		domain = sass_env.cdn_domain;
	}
	console.log( 'Domain set to: ', domain );


	// GULP TASKS

	/**
	 * Command: `gulp`
	 * Default Task.
	 */
	gulp.task( 'default', 'Process Sass, and Javascript', ['js', 'sass'], function( cb ) {
		install_git_hooks( cb );
	});

	/**
	 * Command: `clean`
	 * Removes process blocks.
	 */
	gulp.task( 'clean', 'Removes process blocks.', function( cb ) {
		clean();
		cb();
	});

	/**
	 * Command: `gulp sass`
	 *
	 * Task to compile, autoprefix, and minify Sass.
	 * Also uses a unique filename every time to "bust the cache"
	 */
	gulp.task( 'sass', 'Process Sass, AutoPrefix, and Minify', function( done ) {

		if ( has_block( 'sass' ) ) { return done(); }

		// Prevent this task from running in another terminal
		add_block( 'sass' );

		// Make sure css directory exists
		if ( ! directory_exists( location.css ) ) { mkdir( location.css ); }

		// Update unique name every time
		var css_unique_hash = Date.now();

		var sassOptions = {
			includePaths: require( 'node-neat', 'node-bourbon' ).includePaths
		};

		var apOptions = {
			browsers: ['last 2 versions'],
			cascade: false
		};

		var cleancssOptions = {
			advanced: true,
			aggressiveMerging: true,
			benchmark: false,
			// compatibility: 'ie8',
			compatibility: 'ie11',
			debug: true,
			keepBreaks: false,
			keepSpecialComments: false,
			level: 2,
			mediaMerging: true,
			restructuring: true,
			roundingPrecision: 5,
			semanticMerging: false
		};

		var browserOptions = {
			stream: true
		};

		var stream_error = false;
		var stream = gulp
			.src( location.sass + '/**/*.scss' )
			.pipe( plumber({ errorHandler: function( error ) {
					stream_error = true;
					display_errors.call( this, error );
				}}))
			.pipe( sourcemaps.init() )
			.pipe( sass( sassOptions ).on( 'error', sass.logError ) )
			.pipe( autoprefixer( apOptions ) )
			.pipe( vfs.dest( location.css ) )  // Save not-yet-minified css
			.pipe( cleancss( cleancssOptions ) )
			.pipe( rename( function( path ) { path.basename = path.basename + '-' + css_unique_hash + '.min'; } ) )
			.pipe( sourcemaps.write( '.' ) )
			.pipe( vfs.dest( location.css ) )  // Save processed css
			.pipe( ignore.exclude( '*.map' ) )  // Don't gzip map files (Older Macs struggle with gziped map files)
			.pipe( gzip() )
			.pipe( vfs.dest( location.css ) )  // Save gzipped processed css
			.pipe( browserSync.reload( browserOptions ) )
			.on( 'end', function() {

				// On errors, clear block, but do nothing else
				if ( stream_error ) { remove_block( 'sass' ); return; }

				// Retrieve the current (soon-to-be-old) css hash, don't delete these files
				var css_unique_hash_old = css_unique_hash
				if ( directory_exists( location.css + '/css.hash' ) ) {
					css_unique_hash_old = fs.readFileSync( location.css + '/css.hash', 'utf8' )
						.replace(/(\r\n\t|\n|\r\t)/gm,"");
				}

				// Save the previous css hash.
				fs.rename( location.css + '/css.hash', location.css + '/css.hash~', function() {
						// Save the new css hash
						fs.writeFileSync( location.css + '/css.hash', css_unique_hash );
					});

				// Remove previously compiled stylesheets and sourcemaps
                if ( sass_env.env !== 'production' ) {
	                console.log( 'removing old compiled css and maps' );				
					del([
						location.css + '/**/*.map',
						location.css + '/**/*.map.gz',
						location.css + '/**/*.min.css',
						location.css + '/**/*.css.gz',
						'!' + location.css + '/**/*' + css_unique_hash + '*',
						'!' + location.css + '/**/*' + css_unique_hash_old + '*',
						'!' + location.css + '/style.css.map',
						'!' + location.css + '/css.hash~',
					]);
				}

				// List the resulting file sizes
				spawn( 'du', [ '-sh', '--apparent-size', location.css + '/*-' + css_unique_hash + '.min.css' ], { shell: true } )
					.stdout.on( 'data', function ( data ) {
						console.log( '\nCompiled File sizes:' );
						console.log( data.toString() );
					});

				remove_block( 'sass' );
			});

		return stream;
	});

	/**
	 * Command: `gulp js`
	 *
	 * Task that to combine, minify, and create sourcemaps for JavaScript files.
	 * Also uses a unique filename every time to "bust the cache"
	 *
	 * NOTE: All plugins between sourcemap.init() and sourcemaps.write() must
	 * be supported by sourcemap.
	 */
	gulp.task( 'js', 'Combine, minify, and create sourcemaps for JavaScript files', function( done ) {

		if ( has_block( 'js' ) ) { return done(); }

		// Prevent this task from running in another terminal
		add_block( 'js' );

		// Update unique name every time
		var js_unique_hash = Date.now();
		var js_unique_name = 'global-' + js_unique_hash + '.min.js';

		// Make sure js directory exists
		if ( ! directory_exists( location.js.root ) ) { mkdir( location.js.root ); }

		// Global sources stream
		var global_sources_error = false;
		var global_sources_stream = gulp
			.src( location.js.global_sources )
			.pipe( plumber({ errorHandler: function( error ) {
					global_sources_error = true;
					display_errors.call( this, error );
				}}))
			.pipe( sourcemaps.init() )
			.pipe( babel({ presets: ['es2015'] }) )
			.pipe( insert.prepend( ' //    Please use global/scripts.js\n\n' ) )
			.pipe( insert.prepend( ' // !! It will be overrideen by gulp !!\n' ) )
			.pipe( insert.prepend( ' // !! DO NOT EDIT THIS FILE DIRECTLY !!\n' ) )
			.pipe( concat( 'global.js' ) )
			.pipe( vfs.dest( location.js.root ) )  // Save global.js
			.pipe( uglify() )
			.pipe( rename( js_unique_name ) )
			.pipe( sourcemaps.write( '.' ) )
			.pipe( vfs.dest( location.js.root ) )  // Save global.min.js
			.pipe( ignore.exclude( '*.map' ) )  // Don't gzip map files (Older Macs struggle with gziped map files)
			.pipe( gzip() )
			.pipe( vfs.dest( location.js.root ) ); // Save gzipped processed global.min.js.gz

		// sources stream (non global)
		var sources_error = false;
		var sources_stream = gulp
			.src( location.js.sources )
			.pipe( plumber({ errorHandler: function( error ) {
					sources_error = true;
					display_errors.call( this, error );
				}}))
			.pipe( sourcemaps.init() )
			.pipe( rename( function( path ) { path.basename = path.basename + '-' + js_unique_hash + '.min'; } ) )
			.pipe( uglify() )
			.pipe( sourcemaps.write( '.' ) )
			.pipe( vfs.dest( location.js.root + '/vendors/' ) )  // Save *.min.js
			.pipe( ignore.exclude( '*.map' ) )  // Don't gzip map files (Older Macs struggle with gziped map files)
			.pipe( gzip() )
			.pipe( vfs.dest( location.js.root + '/vendors/' ) ); // Save gzipped processed *.min.js.gz

		return es.merge( global_sources_stream, sources_stream )
				 .on( 'end', function() {

					// On errors, clear block, but do nothing else
					if ( global_sources_error || sources_error ) { remove_block( 'js' ); return; }

					// Retrieve the current (soon-to-be-old) js hash, don't delete these files
					var js_unique_hash_old = js_unique_hash
					if ( directory_exists( location.js.root + '/js.hash' ) ) {
						js_unique_hash_old = fs.readFileSync( location.js.root + '/js.hash', 'utf8' )
							.replace(/(\r\n\t|\n|\r\t)/gm,"");
					}

					// Remove old compiled scripts
                    if ( sass_env.env !== 'production' ) {
                        console.log( 'removing old compiled scripts' );
						del([
							location.js.root + '/*.map',
							location.js.root + '/*.map.gz',
							location.js.root + '/global*.js',
							location.js.root + '/global*.min.js.gz',
							location.js.root + '/vendors/*/*.map',
							location.js.root + '/vendors/*/*.min.js',
							location.js.root + '/vendors/*/*.min.js.gz',
							'!' + location.js.root + '/*' + js_unique_hash + '*',
							'!' + location.js.root + '/*' + js_unique_hash_old + '*',
							'!' + location.js.root + '/vendors/*/*' + js_unique_hash + '*',
							'!' + location.js.root + '/vendors/*/*' + js_unique_hash_old + '*',
							'!' + location.js.root + '/global.js',
						]);
					}

					// Save the previous js hash.
					fs.rename( location.js.root + '/js.hash', location.js.root + '/js.hash~', function() {
							// Update js hash file with new successfully compiled scripts
							fs.writeFileSync( location.js.root + '/js.hash', js_unique_hash );
						});

					// List the resulting file sizes
					spawn( 'du', [ '-sh', '--apparent-size', location.js.root + '/*-' + js_unique_hash + '.min.js' ], { shell: true } )
						.stdout.on( 'data', function ( data ) {
							console.log( '\nCompiled Global JS size:' );
							console.log( data.toString() );
						});
					spawn( 'du', [ '-sh', '--apparent-size', location.js.root + '/vendors/*/*-' + js_unique_hash + '.min.js' ], { shell: true } )
						.stdout.on( 'data', function ( data ) {
							console.log( '\nCompiled Vendor JS File sizes:' );
							console.log( data.toString() );
						});

					remove_block( 'js' );
				 });
	});


	/**
	 * Command: `gulp browserSync`
	 * Task to run browserSync.
	 */
	gulp.task( 'browserSync', false, function() {
		browserSync.init({
			proxy: location.local_url
		});
	});

	/**
	 * Command: `gulp lint`
	 * Task to run linting on javascript files
	 */
	gulp.task( 'lint', 'Lint your JavaScript... and weep.', function( done ) {
		var stream = gulp
			.src( location.js.sources )
			.pipe( jshint() )
			.pipe( jshint.reporter( 'default' ) );

		return stream;
	});


	/**
	 * Command: `gulp imagesync`
	 * Task to run imagesync.
	 */
	gulp.task( 'imagesync', 'Syncs Images from Staging Server', shell.task([
		'cd ../../uploads',
		'sh ./sync_images.sh',
		'cd -'
	], {
		verbose: true
	}));


	/**
	 * Command: `gulp linkcheck`
	 * Task to test for broken links
	 */
	gulp.task( 'linkcheck', 'Checks for broken links', function( cb ) {
		var crawler = new Crawler( location.local );
		var insecureResources = []; // hope for the best!
		crawler
			.on( 'fetchstart', function( queueItem, response ) {
				var secure = true; // hope for the best!
				var insecureResources = []; // hope for the best!
			})
			.on( 'fetch404', function( queueItem, response ) {
				console.log( '\nStatus code: ' + response.statusCode );
				console.log( 'Resource [' + queueItem.url + '] @ [' + queueItem.referrer + ']' );
			})
			.on( 'fetcherror', function( queueItem, response ) {
				console.log( '\nStatus code: ' + response.statusCode );
				console.log( 'Fetch error for resource [' + queueItem.url + '] @ [' + queueItem.referrer + ']' );
			})
			.on( 'fetchheaders', function( queueItem, responseObject ) {
				if( argv.ssl === 'true' && queueItem.protocol === 'http' ){
					console.log( queueItem.protocol + ' @ ' + queueItem.url );
				}
			})
			.on( 'discoverycomplete', function( queueItem, resources ) {
				/*if( argv.ssl === 'true' ){
					for( var i = 0, len = resources.length; i < len; i++ ){
						if ( resources[i].startsWith( 'http:' ) ){
							secure = false;
							if ( insecureResources.indexOf( resources[i] ) === -1 ) {
								insecureResources.push( resources[i] );
							}
						}else{

						}
					}
					if( secure ){
						// Utility function that downloads a URL and invokes
						// callback with the data.
						function download( url, callback ) {
							http.get( url, function( res ) {
								var data = "";
								res.on( 'data', function( chunk ) {
									data += chunk;
								});
								res.on( "end", function() {
									request({
										uri: url,
									}, function( error, response, data ) {
										//   console.log( data );
										var $ = cheerio.load( data );

										// var data = $( "#content" ).text();
										// data = data.replace( /\t/g, '' );
										callback( data );
									});
								});
							}).on( "error", function() {
								callback( null );
							});
						}
						// download( url, function( data ) {
						// 	console.log( data );
						// });
						secure = false;
					}
					console.log( 'insecureResources: ' + insecureResources.length );
				}*/
			})
			.on( 'queueadd', function( queueItem ) {
				// console.log( queueItem.url );
			})
			.on( 'complete', function() {
				console.log( '\nAll Done!' );
				cb();
			});


		crawler.start();
	}, {
	options: {
		'ssl=false': 'set true to check ssl validation',
	}
	});

	/**
	 * Command: `gulp linkcheck`
	 * Task to test for broken links
	 */
	gulp.task( 'spellcheck', 'Checks for spelling errors', function( cb ) {
		var crawler = new Crawler( location.local );
		crawler.discoverResources = function( buffer, queueItem ) {
			var $ = cheerio.load( buffer.toString( "utf8" ) );

			return $( "a[href]" ).map( function () {
				return $( this ).attr( "href" );
			}).get();

		};
		crawler
			.on( 'fetch404', function( queueItem, response ) {
				console.log( '\nStatus code: ' + response.statusCode );
				console.log( 'Resource [' + queueItem.url + '] @ [' + queueItem.referrer + ']' );
			})
			.on( 'fetcherror', function( queueItem, response ) {
				console.log( '\nStatus code: ' + response.statusCode );
				console.log( 'Fetch error for resource [' + queueItem.url + '] @ [' + queueItem.referrer + ']' );
			})
			.on( 'discoverycomplete', function( queueItem, resources ) {
				var http = require( "http" );

				// Utility function that downloads a URL and invokes
				// callback with the data.
				function download( url, callback ) {
					http.get( url, function( res ) {
						var data = "";
						res.on( 'data', function( chunk ) {
							data += chunk;
						});
						res.on( "end", function() {
							request({
								uri: url,
							}, function( error, response, data ) {
								//   console.log( data );
								var $ = cheerio.load( data );

								var data = $( "#content" ).text();
								data = data.replace( /\t/g, '' );
								// console.log( data );
								callback( data );
							});
						});
					}).on( "error", function() {
						callback( null );
					});

				}
				var url = queueItem.url;
				var wordsToCheck = "Please check: ";

				download( url, function( data ) {

					thisHTML = data;

					// It will use the first rule with a matching regex, so go from more specific
					// to more catch-all. The ^ anchor before every regex is required!
					var rules = [
						[/^$/, function( match ) {
							return lexing.Token( 'EOF', null ); // Stops While
						}],
						[/^\s+/, function( match ) {
							return null; // ignore whitespace
						}],
						[/^[^!"#$%&()*+,\-./:;<=>?@[\\\]\^_`{|}~\s]+/, function( match ) { // removed ' for apostrophe
							return lexing.Token( 'WORD', match[0] );
						}],
						[/^./, function( match ) {
							//return lexing.Token( 'PUNCTUATION', match[0] );
							return null;
						}],
					];

					var tokenizer = new lexing.Tokenizer( rules );
					var input = new lexing.StringIterator( thisHTML );
					var output = tokenizer.map( input );
					var uniqueWords = [];

					SpellChecker.getDictionary( "en-US" , location.js.root + '', function( err, dictionary ) {
						if( !err ) {
							dictionary = dictionary;
							do {
								var token = output.next();
								word = token.value;

								if( word ) {
									var misspelled = ! dictionary.spellCheck( word );
									if( misspelled ) {
										wordsToCheck = wordsToCheck + '' + word + '  |  ';
										if ( uniqueWords.indexOf( word ) === -1 ) {
											x = {'word': word, 'suggestion': dictionary.getSuggestions( word )};
											uniqueWords.push( x );
										} // else word exists
									}
								}
							} while ( token.name !== 'EOF' );
							console.log( '\n\n' + gutil.colors['white'].bold( url ) + '' );
							console.log( '\n--------------------------------------------------' );
							console.table( uniqueWords );
							console.log( '--------------------------------------------------\n' );
						}else{
							console.log( 'Error getting Dictionary!' );
						}
					});

				});
			})
			.on( 'queueadd', function( queueItem ) {
				// console.log( queueItem.url );
			})
			.on( 'complete', function() {
				console.log( '\nAll Done!' );
				cb();
			});


		crawler.start();
	});


	/**
	 * Command: `gulp validate`
	 * Task to validate home page
	 */
	gulp.task( 'validate', 'Validates the HTML on the home page', function() {
		validator({
			data: request( location.local ),
			format: 'json'
		})
		.then( function( response ) {  // Format and display output
			var result = response.messages;

			String.prototype.uppercase = function() {
				return this.charAt( 0 ).toUpperCase() + this.slice( 1 );
			};

			for ( i = 0, l = result.length; i < l; i++ ) {
				var color = 'white';
				if ( result[i].subtype === 'warn' ) { color = 'yellow'; }
				if ( result[i].type === 'error' ) { color = 'red'; }
				if ( result[i].type === 'non-document-error' ) { color = 'magenta'; }

				console.log( '\n\n     --------------------------------------------------' );
				console.log( '     [' + gutil.colors[color].bold( result[i].type.uppercase() ) + '] ' + result[i].message );
				console.log( '\n     LINE: ' + result[i].lastLine + ' COL: ' + result[i].lastColumn );
				var extract = result[i].extract.substr( 0, result[i].hiliteStart ) +
					gutil.colors['bg' + color.uppercase()]( result[i].extract.substr( result[i].hiliteStart, result[i].hiliteLength ) ) +
					result[i].extract.substr( result[i].hiliteStart + result[i].hiliteLength );
				console.log( '     ' + extract );
			}
		})
		.catch( function( error ) {
			console.error( error );
		});
	});


	/**
	 * Command: `gulp githooks`
	 * Task to install gulp githooks. Only needs to be run once, but doesn't
	 * hurt to be run more than once. (This is run in the default task)
	 */
	gulp.task( 'githooks', 'Task to install gulp githooks. Only needs to be run once.', function( cb ) {
		return install_git_hooks( cb );
	});



	// WATCH TASKS

	/**
	 * Command: `gulp watch`
	 * Task to watch for changes in CSS and JavaScript files.
	 *
	 * There is always the potential for this to create a race condition that
	 * breaks the build. For instance, if someone was able to save a javascript
	 * file and a sass file at the same time, this could lead to trying to
	 * update the src location (in Base/Theme.php) and one task overwriting the
	 * other. But this seems mostly unlikely.
	 */
	gulp.task( 'watch', 'Runs BrowserSync and Watches JavaScript and SASS files for changes and runs corresponding tasks', ['js', 'sass', 'browserSync'], function() {
		gulp.watch( location.js.global_sources.concat( location.js.sources ), ['js'] )
			.on( 'change', display_file_change );
		gulp.watch( location.sass + '/**/*.scss', ['sass'] )
			.on( 'change', display_file_change );
	});


	/**
	 * Command: `gulp watch-only`
	 * Task to watch sass and js files for changes *without* running browsersync.
	 */
	gulp.task( 'watch-only', 'Watches JavaScript and SASS files for changes and runs corresponding tasks', ['js', 'sass'], function() {
		gulp.watch( location.js.global_sources.concat( location.js.sources ), ['js'] )
			.on( 'change', display_file_change );
		gulp.watch( location.sass + '/**/*.scss', ['sass'] )
			.on( 'change', display_file_change );
	});


	/**
	 * Command: `gulp sass:watch`
	 * Task to watch sass for changes and run browserSync, sasss, and js tasks.
	 */
	gulp.task( 'sass:watch', 'Process Sass and Watch for changes', ['browserSync', 'sass'], function() {
		gulp.watch( location.sass + '/**/*.scss', ['sass'] )
			.on( 'change', display_file_change );
	});


	/**
	 * Handler function to handle file change events.
	 */
	function display_file_change( event ) {
		var path = event.path;
		console.log( '\n' + path.substring( path.lastIndexOf( '/' ) + 1 ) + ' was ' + event.type );
	}


	/**
	 * Displays errors (to be used with plumber).
	 */
	function display_errors( error ) {
		if ( error && error.line !== undefined && error.message !== undefined ) {
			console.log(
				'[' + gutil.colors['red'].bold('ERROR') + ']' +
				' Line: ' + gutil.colors['magenta'].bold( error.line ) +
				' File: ' + gutil.colors['magenta'].bold( error.file ) + '.\n'
			);
		} else {
			console.log( error );
		}
		this.emit( 'end' );
	}


	/**
	 * Tests to see if a file exists.
	 */
	function file_exists( file ) {
		try {
			fs.accessSync( file );
			return true;
		} catch ( e ) {
			return false;
		}
	}


	/**
	 * Tests to see if a directory exists.
	 */
	function directory_exists( file ) {
		return fs.existsSync( file );
	}

	/**
	 * Creates all of the directories in the path recursively.
	 */
	function mkdir( path ) {
		var paths = path.split( '/' );
		var fullPath = '';

		paths.forEach( function( path ) {
			if (fullPath === '') {
				fullPath = path;
			} else {
				fullPath = fullPath + '/' + path;
			}

			if (! directory_exists( fullPath ) ) {
				fs.mkdirSync(fullPath);
			}
		});
	};


	/**
	 * Runs the slate git hook installer script
	 *
	 * @param function cb - a callback function to run after completion
	 */
	function install_git_hooks( cb ) {
		exec( 'env -u GIT_DIR git rev-parse --show-toplevel', function( error, result, stderr ) {
			spawn( result.replace( /(\r\n|\n|\r)/, "" ) + '/.dbs/install_git_hooks.sh', [], { stdio: 'inherit'} )
				.on( 'close', function( data ) { cb(); } );
		});
	}

	/**
	 * Checks to see if file .running_{block_type} exists.
	 *
	 * This is set when a particular action is being run to prevent it from
	 * running when it is already running.
	 */
	function has_block( block_type ) {
		if ( file_exists( './.running_' + block_type) ) {
			console.log( '\n      [WARN] Gulp task "' + block_type + '" appears to be running in another terminal.' );
			console.log( '             Please wait until this task completes or remove the file "./.running_' + block_type + '".\n' );
			console.log( '      ** NOTE: If you are not running gulp in another terminal and you did not just change **' );
			console.log( '      **       branches or complete a merge, and you cannot get gulp to work, you may need **' );
			console.log( '      **       to run `gulp clean`.                                                        **\n' );

			return true;
		} else {
			return false;
		}
	}


	/**
	 * Creates the file .running_{block_type}
	 */
	function add_block( block_type ) {
		fs.writeFileSync( './.running_' + block_type, '' );
	}


	/**
	 * Removes the file .running_{block_type}
	 */
	function remove_block( block_type ) {
		fs.unlink( './.running_' + block_type, function( error ) {
			if ( error && error.code != 'ENOENT' ) { console.error( 'Error occurred while trying to remove file.' ); }
		});
	}


	/**
	 * Runs cleanup during interrupted execution.
	 */
	function clean() {
		console.log( 'Removing process blocks...' );
		remove_block( 'sass' );
		remove_block( 'js' );
	}

})();
