import gulp from 'gulp';
import babel from 'gulp-babel';
import revAll from 'gulp-rev-all';
import sass from 'gulp-sass';
import util from 'gulp-util';
import clean from 'gulp-clean';
import browserSync from 'browser-sync';
import child from 'child_process';
import _ from 'lodash';
import config from './config.json';

const paths = {
  template: 'templates/main'
};
paths.dist = `${paths.template}/build/assets`;
paths.rev = `${paths.template}/build/rev`;

function delegateMockupBuild(cb) {
}

gulp.task('build:clean', () => {
  return gulp.src([paths.dist, paths.rev])
    .pipe(clean());
});

gulp.task('build:mockup:delegate', (cb) => {
  process.chdir('mockup');
  const proc = child.exec(config.mockup.buildCommand, (err) => {
    if (err) return cb(err);
    cb();
  });
  process.chdir('..');
  proc.stdout.on('data', function (data) {
    util.log('mockup:', data.toString().slice(0, -1));
  });
});

gulp.task('build:mockup', ['build:mockup:delegate'], () => {
  return gulp.src(`mockup/${config.mockup.buildGlob}`)
    .pipe(gulp.dest(paths.dist));
});

gulp.task('build:js', () => {
  return gulp.src('assets/js/**/*.js')
    .pipe(babel({
      presets: ['es2015']
    }))
    .pipe(gulp.dest(`${paths.dist}/js`));
});

gulp.task('build:images', () => {
  return gulp.src('assets/images/**', {base: 'assets'})
    .pipe(gulp.dest(paths.dist));
});

gulp.task('build', ['build:mockup', 'build:js', 'build:images']);

gulp.task('rev', ['build'], () => {
  return gulp.src(`${paths.dist}/**`)
    .pipe(revAll.revision())
    .pipe(gulp.dest(paths.rev))
    .pipe(revAll.manifestFile())
    .pipe(gulp.dest(paths.rev));
});

gulp.task('release', ['build', 'rev']);

gulp.task('dev:sass', () => {
  return gulp.src('mockup/css/*.scss')
    .pipe(sass())
    .pipe(gulp.dest(`${paths.dist}/css`))
    .pipe(browserSync.stream());
});

gulp.task('browser-sync', () => {
  browserSync.init({
    proxy: 'bitrix.localhost', // TODO config
    open: false
  });
});

gulp.task('dev', ['build', 'browser-sync'], () => {
  gulp.watch('mockup/css/*.scss', ['dev:sass']);
  // TODO watch mockup js
  gulp.watch('assets/js/**/*.js', ['build:js']);
  gulp.watch([`${paths.template}/**/*.php`, `${paths.template}/**/*.twig`]).on('change', browserSync.reload);
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

