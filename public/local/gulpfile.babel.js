import gulp from 'gulp';
import babel from 'gulp-babel';
import rev from 'gulp-rev';
import revReplace from 'gulp-rev-replace';
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

gulp.task('clean', () => {
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

gulp.task('revision:rev', ['build'], () => {
  return gulp.src(`${paths.dist}/**`)
    .pipe(rev())
    .pipe(gulp.dest(paths.rev))
    .pipe(rev.manifest())
    .pipe(gulp.dest(paths.rev));
});

gulp.task('revision:replace', ['revision:rev'], () => {
  const manifest = gulp.src(`${paths.rev}/rev-manifest.json`);
  // skip *.js, replacing naively might break the scripts, had a bad experience with gulp-rev-all
  return gulp.src(`${paths.rev}/**/*.css`)
    .pipe(revReplace({manifest: manifest}))
    .pipe(gulp.dest(paths.rev))
});

gulp.task('revision', ['revision:rev', 'revision:replace']);

gulp.task('release', ['build', 'revision']);

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

