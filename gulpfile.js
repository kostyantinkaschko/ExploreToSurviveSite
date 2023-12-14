import browserSync from "browser-sync";
import gulp from "gulp";
import del from "del";
import pug from "gulp-pug";
import * as sass from "sass";
import gulpSass from "gulp-sass";
import autoprefixer from "gulp-autoprefixer";
import concat from "gulp-concat";
import uglify from "gulp-uglify-es";
import imagemin from "gulp-imagemin";
import cache from "gulp-cache";
import gcmq from "gulp-group-css-media-queries";
import cleanCSS from "gulp-clean-css";
import php from "gulp-connect-php";

const sassCompiler = gulpSass(sass);

export const browserSyncFunc = () => {
  browserSync({
    proxy: "https://ets.sellprod",
    notify: false
  });
};

export const html = () => {
  return gulp
    .src(["src/pug/*.pug"])
    .pipe(pug({}))
    .pipe(gulp.dest("docs"))
    .pipe(browserSync.reload({
      stream: true
    }));
};

export const css = () => {
  return gulp
    .src([
      "src/sass/*.css",
      "src/sass/*.sass"
    ])
    .pipe(sassCompiler({
      outputStyle: "compressed" // expanded, compact
    }).on("error", sassCompiler.logError))
    .pipe(autoprefixer(["last 15 version"], {
      cascade: true
    }))
    .pipe(gcmq('style.css'))
    .pipe(concat("style.css"))
    .pipe(cleanCSS({
      compatibility: "ie8"
    }))
    .pipe(gulp.dest("docs/css"))
    .pipe(browserSync.reload({
      stream: true
    }));
};

export const js = () => {
  return gulp
    .src([
      "src/js/**/*.js"
    ])
    .pipe(uglify.default())
    .pipe(concat("scripts.js"))
    .pipe(gulp.dest("docs/js"))
    .pipe(browserSync.reload({
      stream: true
    }));
};

export const files = () => {
  return gulp
    .src(["src/*.*"], {
      dot: true
    })
    .pipe(gulp.dest("docs"))
    .pipe(browserSync.reload({
      stream: true
    }));
};

export const fonts = () => {
  return gulp
    .src(["src/fonts/**/*.*"], {
      dot: true
    })
    .pipe(gulp.dest("docs/fonts"))
    .pipe(browserSync.reload({
      stream: true
    }));
};

export const deleteFiles = () => {
  return del("docs");
};

export const phpTask = () => {
  return gulp
    .src(["src/php/**/*.php"], {
      dot: true
    })
    .pipe(gulp.dest("docs/php"))
    .pipe(browserSync.reload({
      stream: true
    }));
};

export const images = () => {
  return gulp
    .src(["src/img/**/*.*", "src/img/webp/**/*.*"], {
      dot: true
    })
    .pipe(cache(imagemin()))
    .pipe(gulp.dest("docs/img"))
    .pipe(browserSync.reload({
      stream: true
    }));
};

export const clear = () => {
  return cache.clearAll();
};

export const watch = () => {
  gulp.watch("src/sass/**/*.sass", gulp.parallel(css));
  gulp.watch("src/js/**/*.js", gulp.parallel(js));
  gulp.watch("src/pug/**/*.pug", gulp.parallel(html));
  gulp.watch("src/*.*", gulp.parallel(files));
  gulp.watch("src/fonts/**/*.*", gulp.parallel(fonts));
  gulp.watch("src/img/*.*", gulp.parallel(images));
  gulp.watch("src/php/**/*.php", gulp.parallel(phpTask));
};

export const phpServer = () => {
  php.server({
    base: "src/php/*", // Змініть на вашу теку з PHP-файлами
    port: 8000, // Виберіть порт, який вам потрібен
    keepalive: true
  });
};

export default gulp.series(
  deleteFiles,
  gulp.parallel(
    phpServer,
    watch,
    html,
    css,
    js,
    files,
    fonts,
    images,
    phpTask,
    browserSyncFunc
  )
);
