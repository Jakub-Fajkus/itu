import gulp from 'gulp';
import postcss from 'gulp-postcss';
import sourcemaps from 'gulp-sourcemaps';
import chalk from 'chalk';
import notifier from 'node-notifier';
import plumber from 'gulp-plumber';
import precss from 'precss';
import postcssInlineSvg from 'postcss-inline-svg';
import postcssMixins from 'postcss-mixins';
import notify from 'gulp-notify';
import nano from 'gulp-cssnano';
import lec from 'gulp-line-ending-corrector';
import postcssCalc from 'postcss-calc';
import postcssHexrgba from 'postcss-hexrgba';
import postcssColorFunction from 'postcss-color-function';
import postcssCssNext from 'postcss-cssnext';
import { default as postcssSprites } from 'postcss-sprites';
import svgmin from 'gulp-svgmin';

const paths = {
  project: './web/',
};


gulp.task('default', () => {
});

gulp.task('compile:styles:project', () => {
  let processors = [
    precss,
    postcssMixins,
    postcssCalc,
    postcssHexrgba,
    postcssColorFunction,
    postcssInlineSvg,
    postcssCssNext
  ];

  gulp.src(paths.project + '/src/css/*.css')
    .pipe(plumber({errorHandler: swallowError}))
    .pipe(sourcemaps.init())
    .pipe(postcss(processors))
    .pipe(nano())
    .pipe(plumber.stop())
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(paths.project + '/css'))
    .pipe(notify({title: 'Gulp DONE (' + getNotifyTime() + ')', message: 'compile:styles:project', onLast: true}))
    .pipe(lec({eolc: 'LF', encoding: 'utf8'}));
});

gulp.task('svgmin', function () {
  return gulp.src(paths.project + '/src/svg-orig/*.svg')
    .pipe(svgmin({
      plugins: [{
        removeDoctype: false
      }, {
        removeComments: true
      }, {
        cleanupNumericValues: {
          floatPrecision: 2
        }
      }, {
        convertColors: {
          names2hex: false,
          rgb2hex: false
        }
      }]
    }))
    .pipe(gulp.dest(paths.project + '/src/svg'));
});


gulp.task('live', () => {
  gulp.watch(paths.project + '/src/css/**/*.css', ['compile:styles:project']);
});

let swallowError = error => {
  console.log(error.message);
  notify.onError('Error: ' + error.message);
  sendNotify('Gulp ERROR:', chalk.stripColor(error.message));
}

let sendNotify = (title, message) => {
  notifier.notify({ title, message });
}

let getNotifyTime = () => {
  var d = new Date;

  return [d.getHours(), d.getUTCMinutes(), d.getUTCSeconds()].join(':');
}
