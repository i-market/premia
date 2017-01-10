import gulp from 'gulp';
import babel from 'gulp-babel';
import revAll from 'gulp-rev-all';
import sass from 'gulp-sass';
import util from 'gulp-util';
import browserSync from 'browser-sync';
import child from 'child_process';
import _ from 'lodash';

const config = {
  mockupBuildCommand: 'gulp sass && gulp css && gulp dist'
};
const paths = {
  template: 'templates/main'
};
paths.dest = `${paths.template}/assets`;

gulp.task('sass', () => {
  return gulp.src('mockup/css/*.scss')
    .pipe(sass())
    .pipe(gulp.dest(`${paths.dest}/css`))
    .pipe(browserSync.stream());
});

gulp.task('js', () => {
  return gulp.src('mockup/js/**/*.js')
    .pipe(gulp.dest(`${paths.dest}/js`));
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

gulp.task('dev', ['sass', 'js', 'browser-sync'], () => {
  gulp.watch('mockup/css/*.scss', ['sass']);
  gulp.watch('mockup/js/**/*.js', ['js']);
  gulp.watch([`${paths.template}/**/*.php`, `${paths.template}/**/*.twig`]).on('change', browserSync.reload);
});

const build = {
  combine: () => {
    gulp.src('mockup/dist/**/*.{js,css,png,jpg,jpeg}')
      .pipe(gulp.dest(paths.dest))
      .pipe(revAll.revision())
      .pipe(gulp.dest(paths.dest))
      .pipe(revAll.manifestFile())
      .pipe(gulp.dest(paths.dest));
    gulp.src('assets/**/*')
      .pipe(gulp.dest(paths.dest));
  }
};

gulp.task('build:combine', build.combine);

gulp.task('build', () => {
  process.chdir('mockup');
  const proc = child.exec(config.mockupBuildCommand);
  process.chdir('..');
  proc.stdout.on('data', function (data) {
    util.log('mockup:', data.toString().slice(0, -1));
  });
  proc.on('close', function (code) {
    const success = code === 0;
    if (success) {
      build.combine();
    }
  });
});
