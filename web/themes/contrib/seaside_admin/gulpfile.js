var gulp = require('gulp');
var $ = require('gulp-load-plugins')();
var del = require('del');
var autoprefixer = require('autoprefixer');

/**
 * @task sass-lint
 * Lint sass, abort calling task on error
 */
gulp.task('sass-lint', function () {
  return gulp.src('static/sass/**/*.s+(a|c)ss')
  // .pipe($.debug())
  .pipe($.sassLint({configFile: './.sass-lint.yml'}))
  .pipe($.sassLint.format())
  .pipe($.sassLint.failOnError());
});

gulp.task('sass-compile', ['sass-lint'], function () {
  // postCss plugins and processes
  var pcPlug = {
    autoprefixer: require('autoprefixer'),
    mqpacker: require('css-mqpacker')
  };
  var pcProcess = [
    pcPlug.autoprefixer({
      browsers: ['> 1%', 'last 2 versions', 'firefox >= 4', 'safari 7', 'safari 8', 'IE 8', 'IE 9', 'IE 10', 'IE 11']
    }),
    pcPlug.mqpacker()
  ];

  return gulp.src('static/sass/**/*.s+(a|c)ss') // Gets all files ending
  .pipe($.sourcemaps.init())
  .pipe($.sass())
  .on('error', function (err) {
    console.log(err);
    this.emit('end');
  })
  .pipe($.postcss(pcProcess))
  .pipe($.sourcemaps.write())
  .pipe(gulp.dest('static/css'));
});

gulp.task('sass-build', ['sass-lint'], function () {
  // postCss plugins and processes
  var pcPlug = {
    autoprefixer: require('autoprefixer'),
    mqpacker: require('css-mqpacker')
  };
  var pcProcess = [
    pcPlug.autoprefixer({
      browsers: ['> 1%', 'last 2 versions', 'firefox >= 4', 'safari 7', 'safari 8', 'IE 8', 'IE 9', 'IE 10', 'IE 11']
    }),
    pcPlug.mqpacker()
  ];

  return gulp.src('static/sass/**/*.s+(a|c)ss') // Gets all files ending
  .pipe($.sass())
  .on('error', function (err) {
    console.log(err);
    this.emit('end');
  })
  .pipe($.postcss(pcProcess))
  .pipe(gulp.dest('static/css'));
});

/**
 * @task js
 * Do javascript stuff.
 */
gulp.task('js', function () {
  return gulp.src(['static/js/**/*.js', '!static/js/lib/*.js'])
  .pipe($.jshint())
  .pipe($.jshint.reporter('default'))
  .pipe($.uglify());
});

/**
 * @task clean
 * Clean the dist folder.
 */
gulp.task('clean', function () {
  return del(['static/css/*']);
});

/**
 * @task watch
 * Watch files and do stuff.
 */
gulp.task('watch', ['clean', 'sass-compile', 'js'], function () {
  gulp.watch('static/sass/**/*.+(scss|sass)', ['sass-compile']);
  gulp.watch('static/js/**/*.js', ['js']);
});

/**
 * @task watch
 * Watch files and do stuff.
 */
gulp.task('build', ['clean', 'sass-build', 'js'], function () {
});

/**
 * @task default
 * Watch files and do stuff.
 */
gulp.task('default', ['watch']);
