import gulp from 'gulp';
import babel from 'gulp-babel';
import rev from 'gulp-rev';
import sass from 'gulp-sass';
import util from 'gulp-util';
import browserSync from 'browser-sync';
import child from 'child_process';
import _ from 'lodash';

gulp.task('sass', () => {
  return gulp.src('mockup/css/*.scss')
    .pipe(sass())
    .pipe(gulp.dest('templates/main/dev'))
    .pipe(browserSync.stream());
});

gulp.task('css', () => {
  return gulp.src('mockup/css/lib/**/*.css')
    .pipe(gulp.dest('templates/main/dev/lib'));
});

gulp.task('js', () => {
  return gulp.src('mockup/js/**/*.js')
    .pipe(gulp.dest('templates/main/dev'));
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
  const proc = child.spawn('casperjs', ['test'].concat(tests).concat(args));
  proc.stdout.on('data', function (data) {
    util.log('CasperJS:', data.toString().slice(0, -1)); // Remove \n
  });
  proc.on('close', function (code) {
    const success = code === 0; // Will be 1 in the event of failure
    // Do something with success here
  });
});

gulp.task('dev', ['sass', 'css', 'js', 'browser-sync'], () => {
  gulp.watch('mockup/css/*.scss', ['sass']);
  gulp.watch('mockup/js/**/*.js', ['js']);
  gulp.watch(['templates/main/**/*.php', 'templates/main/**/*.twig']).on('change', browserSync.reload);
});

gulp.task('build', () => {
  process.chdir('mockup');
  const proc = child.exec('gulp sass && gulp css && gulp dist');
  process.chdir('..');
  proc.stdout.on('data', function (data) {
    util.log('mockup:', data.toString().slice(0, -1));
  });
  proc.on('close', function (code) {
    const success = code === 0;
    if (success) {
      gulp.src('mockup/dist/**/*.{js,css,png,jpg,jpeg}')
        .pipe(gulp.dest('templates/main/assets'));
      gulp.src('assets/**/*')
        .pipe(gulp.dest('templates/main/assets'));
    }
  });
});
