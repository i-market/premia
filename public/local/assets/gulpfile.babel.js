import gulp from 'gulp';
import babel from 'gulp-babel';
import rev from 'gulp-rev';
import sass from 'gulp-sass';
import browserSync from 'browser-sync';

gulp.task('sass', () => {
  return gulp.src('styles/main.scss')
    .pipe(sass())
    .pipe(gulp.dest('../templates/main/dev'))
    .pipe(browserSync.stream());
});

gulp.task('browser-sync', () => {
  browserSync.init({
    proxy: 'bitrix.localhost', // TODO config
    open: false
  });
});

gulp.task('dev', ['sass', 'browser-sync'], function() {
  gulp.watch('styles/main.scss', ['sass']);
  gulp.watch('../templates/main/**/*.php').on('change', browserSync.reload);
});
