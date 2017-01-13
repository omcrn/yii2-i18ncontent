/**
 *  Welcome to your gulpfile!
 *  The gulp tasks are split into several files in the gulp directory
 *  because putting it all here was too long
 */

'use strict';


var gulp = require('gulp');

var less = require('gulp-less');

/**
 *  Default task clean temporaries directories and launch the
 *  main optimization build task
 */
gulp.task('default', function () {
  gulp.watch('assets/less/**/*.less', ['default']);

  return gulp.src('assets/less/main.less')
    .pipe(less()) // Using gulp-less
    .pipe(gulp.dest('assets/css/'))
});
