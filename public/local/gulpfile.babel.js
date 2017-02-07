import gulp from 'gulp';
import concat from 'gulp-concat';
import babel from 'gulp-babel';
import rev from 'gulp-rev';
import revReplace from 'gulp-rev-replace';
import sass from 'gulp-sass';
import util from 'gulp-util';
import clean from 'gulp-clean';
import uglify from 'gulp-uglify';
import responsive from 'gulp-responsive';
import browserSync from 'browser-sync';
import child from 'child_process';
import _ from 'lodash';
import browserify from 'browserify';
import babelify from 'babelify';
import source from 'vinyl-source-stream';
import buffer from 'vinyl-buffer';
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

gulp.task('build:vendor:js', () => {
  return gulp.src([
    'node_modules/jquery-match-height/dist/jquery.matchHeight.js',
    'node_modules/waypoints/lib/jquery.waypoints.js',
    'node_modules/jquery.counterup/jquery.counterup.js'
  ])
    .pipe(concat('vendor.js'))
    .pipe(uglify())
    .pipe(gulp.dest(`${paths.dist}/js`));
});

gulp.task('build:vendor', ['build:vendor:js']);

gulp.task('build:js', () => {
  return browserify('assets/js/main.js')
    .on('error', (error) => {
      util.log('Browserify:', error.message);
    })
    .transform(babelify, {presets: ['es2015']})
    .bundle()
    .pipe(source('bundle.js'))
    .pipe(buffer())
    .pipe(uglify())
    .pipe(gulp.dest(`${paths.dist}/js`));
});

gulp.task('build:images', () => {
  const plansGlob = 'assets/images/upload/floor-plans/**';
  const restStream = gulp.src(['assets/images/**', `!${plansGlob}`], {base: 'assets'})
    .pipe(gulp.dest(paths.dist));
  const plansStream = gulp.src(plansGlob, {base: 'assets'})
    .pipe(responsive({
      '**': {
        width: 1260
      }
    }, {
      errorOnEnlargement: false
    }))
    .pipe(gulp.dest(paths.dist));
  // return merge(plansStream, restStream);
});

gulp.task('build', ['build:mockup', 'build:js', 'build:images', 'build:vendor']);

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

gulp.task('dev', ['dev:sass', 'browser-sync'], () => {
  gulp.watch('mockup/css/*.scss', ['dev:sass']);
  gulp.watch('assets/images/**', ['build:images']);
  gulp.watch([`${paths.dist}/js/**/*.js`, `${paths.template}/**/*.twig`])
    .on('change', browserSync.reload);
});

// TODO invoke callback when done
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

