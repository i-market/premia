import gulp from 'gulp';
import babel from 'gulp-babel';
import rev from 'gulp-rev';
import sass from 'gulp-sass';
import util from 'gulp-util';
import browserSync from 'browser-sync';
import child from 'child_process';
import _ from 'lodash';

gulp.task('sass', () => {
  return gulp.src('assets/styles/main.scss')
    .pipe(sass())
    .pipe(gulp.dest('templates/main/dev'))
    .pipe(browserSync.stream());
});

gulp.task('browser-sync', () => {
  browserSync.init({
    proxy: 'bitrix.localhost', // TODO config
    open: false
  });
});

gulp.task('test:e2e', () => {
  gulp.src('e2e-tests/*.js')
    .pipe(babel({
      presets: ['es2015']
    }))
    .pipe(gulp.dest('out/e2e-tests'));
  const tests = ['out/e2e-tests/test.js'];
  // pass the arguments to casper
  const args = _.drop(process.argv, 3);
  const casperProc = child.spawn('casperjs', ['test'].concat(tests).concat(args));
  casperProc.stdout.on('data', function (data) {
    util.log('CasperJS:', data.toString().slice(0, -1)); // Remove \n
  });
  casperProc.on('close', function (code) {
    const success = code === 0; // Will be 1 in the event of failure
    // Do something with success here
  });
});

gulp.task('dev', ['sass', 'browser-sync'], () => {
  gulp.watch('assets/styles/main.scss', ['sass']);
  gulp.watch('templates/main/**/*.php').on('change', browserSync.reload);
});
