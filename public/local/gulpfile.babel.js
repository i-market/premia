import gulp from 'gulp';
import concat from 'gulp-concat';
import rev from 'gulp-rev';
import revReplace from 'gulp-rev-replace';
import sass from 'gulp-sass';
import util from 'gulp-util';
import clean from 'gulp-clean';
import uglify from 'gulp-uglify';
import browserify from 'browserify';
import babelify from 'babelify';
import source from 'vinyl-source-stream';
import buffer from 'vinyl-buffer';
import browserSync from 'browser-sync';
import child from 'child_process';
import _ from 'lodash';

const config = {
  'mockup': {
    'buildCommand': 'gulp sass && gulp css && gulp dist',
    // TODO exclude stuff like *.html instead
    'buildGlob': 'dist/**/*.{js,css,png,jpg,jpeg,gif,svg}'
  }
};
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

gulp.task('build:mockup:copy', ['build:mockup:delegate'], () => {
  return gulp.src(`mockup/${config.mockup.buildGlob}`)
    .pipe(gulp.dest(paths.dist));
});

gulp.task('build:mockup', ['build:mockup:copy']);

gulp.task('build:vendor:js', ['build:mockup:copy'], () => {
  const mockup = [
    `${paths.dist}/js/vendor/slick.min.js`,
    `${paths.dist}/js/script.js`
  ];
  return gulp.src(_.concat(mockup, [
    'node_modules/fancybox/dist/jquery.fancybox.js'
  ]))
    .pipe(concat('vendor.js'))
    .pipe(uglify())
    .pipe(gulp.dest(`${paths.dist}/js`));
});

gulp.task('build:vendor:css', ['build:mockup'], () => {
  const mockup = [
    `${paths.dist}/css/lib/normalize.min.css`,
    `${paths.dist}/css/lib/slick.css`
  ];
  return gulp.src(_.concat(mockup, [
    'node_modules/fancybox/dist/jquery.fancybox.css'
  ]))
    .pipe(concat('vendor.css'))
    // keep it in `lib` in case relative paths rely on it being there
    .pipe(gulp.dest(`${paths.dist}/css/lib`));
});

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
  return gulp.src('assets/images/**', {base: 'assets'})
    .pipe(gulp.dest(paths.dist));
});

gulp.task('build:vendor', ['build:vendor:js', 'build:js', 'build:vendor:css']);

gulp.task('build', ['build:mockup', 'build:vendor', 'build:images']);

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

gulp.task('watch:sass', () => {
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

gulp.task('watch', ['watch:sass', 'browser-sync'], () => {
  gulp.watch('mockup/css/*.scss', ['watch:sass']);
  gulp.watch('assets/images/**', ['build:images']);
  gulp.watch('mockup/js/*.js', ['build:vendor:js']);
  gulp.watch([`${paths.dist}/js/**/*.js`, `${paths.template}/**/*.twig`])
    .on('change', browserSync.reload);
});
