const gulp = require('gulp');
const sass = require('gulp-sass');
const browserSync = require('browser-sync').create();
const babel = require('gulp-babel');
const uglify = require('gulp-uglify');
const copy = require('gulp-copy');
const sourcemaps = require('gulp-sourcemaps');
const concat = require('gulp-concat');
const cleanCSS = require('gulp-clean-css');
const rename = require('gulp-rename');
const autoprefixer = require('gulp-autoprefixer');


//task flow //
const watch = gulp.parallel( watchTask, doBrowserSync );

//tasks //

function watchTask() {
  gulp.watch('library/src/scss/views/**/*.scss', doSass );
  gulp.watch('library/src/scss/global/**/*.scss', doSass );
  gulp.watch('library/src/scss/login/login.scss', loginSass );
  gulp.watch('library/src/es6/views/**/*.js', gulp.series( bundle, footer_bundle ) );
  gulp.watch('library/src/es6/global/**/*.js', gulp.series( bundle, footer_bundle ) );
  gulp.watch('library/src/es6/app.js', makeUgly);
  gulp.watch('library/src/es6/footer.js', footerUgly);
  gulp.watch('library/src/es6/login/login.js', loginUgly);

  gulp.watch('library/src/scss/img/*', copyCssImg);
  //gulp.watch('library/src/scss/*.map', copyCssSourcemap); //Style will only ever be changed by doSass
}

const javascript_modules = [
  'library/src/es6/global/modals.js',
  'library/src/es6/views/header.js',
  'library/src/es6/views/video.js',
  'library/src/es6/views/components/right_avatar.js',
  'library/src/es6/views/components/top_logo.js',
  'library/src/es6/views/components/top_navigation.js',
  'library/src/es6/views/components/hero_section.js',
  'library/src/es6/views/components/right_col.js',
  'library/src/es6/views/components/settings.js',
  'library/src/es6/views/dashboard.js',
]
const javascript_close_body_modules = [
  'library/src/es6/views/footer.js',
  'library/src/es6/views/homesplash.js',
  'library/src/es6/countdown.inprogress.js' 
] 

function bundle() {
return gulp.src(javascript_modules)
    .pipe(concat('app.js'))
    .pipe(gulp.dest('library/src/es6/'));
}
function footer_bundle() {
  return gulp.src(javascript_close_body_modules)
      .pipe(concat('footer.js'))
      .pipe(gulp.dest('library/src/es6/'));
  }

function makeUgly(){
  return gulp.src('library/src/es6/app.js')
    .pipe(babel({
      presets: ["@babel/preset-env"]
    }))
    .pipe(uglify())

    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest('library/dist/js/'))
    .pipe(browserSync.reload({
        stream: true
      }));
};
function footerUgly(){
  return gulp.src(['library/src/es6/footer.js'])
    .pipe(babel({
      presets: ["@babel/preset-env"]
    }))
    .pipe(uglify())
    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest('library/dist/js/'))
    .pipe(browserSync.reload({
        stream: true
      }));
};
function loginUgly(){
  return gulp.src( 'library/src/es6/login/login.js' )
    .pipe(babel({
      presets: ["@babel/preset-env"]
    }))
    .pipe(uglify())
    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest('library/dist/js/'))
    .pipe(browserSync.reload({
        stream: true
      }));
};
function copyCssImg(){
  return gulp.src('theme/library/src/assets/*.*')
    .pipe(gulp.dest('theme/library/dist/assets/'));
};
function copyCssSourcemap(){
  return gulp.src('library/src/scss/*.map')
    .pipe(gulp.dest('library/dist/css/'));
};


function doSass() {
    return gulp.src('library/src/scss/app.scss')
      .pipe(sourcemaps.init())
      .pipe(sass({outputStyle: 'compressed'})) // Converts Sass to CSS with gulp-sass
      .pipe(sourcemaps.write({includeContent: false}))
      .pipe(sourcemaps.init({loadMaps: true}))
      .pipe(autoprefixer())
      .pipe(rename({ suffix: '.min' }))
      .pipe(sourcemaps.write('.'))
      .pipe(gulp.dest('library/dist/css'))
      .pipe(browserSync.reload({
        stream: true
      }));
}
function loginSass() {
  return gulp.src('library/src/scss/login/login.scss')
    .pipe(sourcemaps.init())
    .pipe(sass({outputStyle: 'compressed'})) // Converts Sass to CSS with gulp-sass
    .pipe(sourcemaps.write({includeContent: false}))
    .pipe(sourcemaps.init({loadMaps: true}))
    .pipe(autoprefixer())
    .pipe(rename({ suffix: '.min' }))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('library/dist/css'))
    .pipe(browserSync.reload({
      stream: true
    }));
}
function doBrowserSync(done){
  browserSync.init({
    /* server: {
      baseDir: 'app'
    }, 
    proxy: "localhost/NEON", 
    port: 8000
    */
  });
}

exports.watch = watch;