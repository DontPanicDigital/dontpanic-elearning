// Import modules
import gulp            from "gulp";
import gutil           from "gulp-util";
import sass            from "gulp-ruby-sass";
import notifier        from "node-notifier";
import sourcemaps      from "gulp-sourcemaps";
import browserify      from "browserify";
import livereload      from "gulp-livereload";
import babelify        from "babelify";
import uglify          from "gulp-uglify";
import yargs           from "yargs";
import preprocess      from "gulp-preprocess";
import runSequence     from "run-sequence";
import sprity          from "sprity";
import gulpif          from "gulp-if";
import source          from "vinyl-source-stream";
import watchify        from "watchify";
import assign          from "lodash.assign";
import esteWatch       from "este-watch";
import rename          from "gulp-rename";
import imagemin        from "gulp-imagemin";
import pngquant        from "imagemin-pngquant";
import cache           from 'gulp-cache';
import browserSync     from "browser-sync";
import streamify       from "gulp-streamify";
import _               from "lodash";
import loose_envify    from "loose-envify"
import config          from "./config";

const reload    = browserSync.reload;
// Define global
let basePath    = __dirname;
let environment = null;
let args        = yargs.argv;
let settings    = {};

let prepareFiles = () => {
    const min  = environment === "build" ? ".min" : "";
    const path = "www/build";

    const sassFiles = [
        { path: `${basePath}/styles/app.scss`, name: "styles" }
    ];

    let jsFiles = [
        { path: `${basePath}/scripts/app.js`, name: "scripts" }
    ];

    if(args.react) {
        jsFiles.push({ path: `react/src/index.js`, name: "react" });
    }

    const sassFilesSetting = _.map(sassFiles, (file) => {
        return {
            entries: file.path,
            dest:    path,
            bundle:  `${file.name}${min}.css`,
            options: {
                quiet:     true,
                sourcemap: !args.build,
                style:     args.build ? "compressed" : "nested",
                loadPath:  require("node-neat").includePaths,
                require:   "sass-globbing"
            }
        }
    });

    const jsFilesSetting = _.map(jsFiles, (file) => {
        return {
            entries: file.path,
            dest:    path,
            bundle:  `${file.name}${min}.js`
        };
    });

    settings = {
        sass: {
            files: sassFilesSetting
        },

        javascript: {
            files: jsFilesSetting
        }
    };
};

/**
 * @name Sass task
 */
gulp.task("sass", () => {
    let sassFile = (file) => {
        return sass(file.entries, file.options)
        .on("error", function (err) {
            notifier.notify({ "title": "Sass", "message": err.message });
            console.error("Error!", err.message);
        })
        .pipe(sourcemaps.write())
        .pipe(rename(file.bundle))
        .pipe(gulp.dest(file.dest))
        .pipe(reload({ stream: true }))
        .pipe(livereload());
    };

    settings.sass.files.forEach(sassFile);
});

gulp.task("browser-sync", () => {
    browserSync.init({
        proxy: config.proxy
    });
});

/**
 * @name watchify task
 */
gulp.task("watchify", () => {
    let watchifyFile = (file) => {
        let opts    = assign({}, watchify.args, { debug: true });
        let bundler = watchify(browserify(file.entries, opts));

        let rebundle = () => {
            return bundler
            .bundle()
            .on("error", function handleError(err) {
                notifier.notify({ "title": "Javascript", "message": err.toString() });
                gutil.log(gutil.colors.red(err.toString()));
                this.emit("end");
            })
            .pipe(source(file.bundle))
            .pipe(gulp.dest(file.dest))
            .pipe(reload({ stream: true }))
            .pipe(livereload());
        };

        let logger = (msg) => {
            gutil.log("Watchify:", gutil.colors.green(msg));
        };

        bundler
        .transform(babelify, { presets: ["es2015", "react", "stage-0"] })
        .on("update", rebundle)
        .on("log", logger);

        return rebundle();
    };

    settings.javascript.files.forEach(watchifyFile);
});

/**
 * @name Browserify task
 */
gulp.task("browserify", () => {
    let ugl = environment === "build" ? true : false;

    let browserifyFile = (file) => {
        return browserify({ debug: false })
        .transform(loose_envify,{
            NODE_ENV: "production"
        })
        .transform(babelify, { presets: ["es2015", "react", "stage-0"] })
        .require(file.entries, { entry: true })
        .bundle()
        .on("error", function handleError(err) {
            notifier.notify({ "title": "Javascript", "message": err.toString() });
            console.error(err.toString());
            this.emit("end");
        })
        .pipe(source(file.bundle))
        .pipe(gulpif(ugl, streamify(uglify())))
        .pipe(gulp.dest(file.dest));
    };

    settings.javascript.files.forEach(browserifyFile);
});

/**
 * @name Preprocess task
 */
gulp.task("preprocess", () => {
    return gulp.src(`${basePath}/htmls/**/*.html`)
    .pipe(preprocess({ context: { NODE_ENV: environment } }))
    .pipe(gulp.dest(basePath))
    .pipe(reload({ stream: true }))
    .pipe(livereload());
});

/**
 * @name Sprity task
 */
gulp.task("sprity", () => {
    return sprity.src({
        src:                  `${basePath}/assets/images/sprites/*.{png,jpg}`,
        processor:            "sass",
        style:                "sprites.scss",
        template:             `png.hbs`,
        dimension:            [{ ratio: 1, dpi: 192 }, { ratio: 2, dpi: 192 }],
        "lwip-interpolation": "cubic"
    })
    .on("error", (err) => {
        console.log("No sprites images in sprites folder!");
    })
    .pipe(gulpif("*.png", gulp.dest(`${basePath}/assets/images`), gulp.dest(`${basePath}/styles/base`)))
    .pipe(livereload());
});

/**
 * @name Watch task
 */
gulp.task("watch", () => {
    let watchDirs = [`${basePath}/styles/`, `${basePath}/scripts/`];
    if (args.html) {
        watchDirs.push(`${basePath}/htmls/`);
    }

    let watch = esteWatch(watchDirs, (e) => {
        switch (e.extension) {
            case "scss":
                gulp.start("sass");
                break;
            case "html":
                gulp.start("preprocess");
                break;
            case "png":
                gulp.start("sprity");
                break;
        }
    });
    watch.start();
});

/**
 * @name Uglify Task
 */
gulp.task("uglify", function () {
    let uglifyFile = (file) => {
        console.log(file);
        return gulp.src(file.entries)
        .pipe(uglify({ compress: { drop_console: true } }))
        .pipe(gulp.dest(file.dest));
    }

    settings.javascript.uglify.forEach(uglifyFile);
});

/**
 * @name Imagemin Task
 */
gulp.task("imagemin", () => {
    return gulp.src(`${basePath}/assets/images/*`)
    .pipe(cache(imagemin({ progressive: true, use: [pngquant()] })))
    .pipe(gulp.dest(`${basePath}/assets/images`));
});

/**
 * @name Copy Task
 */
gulp.task("copy", function () {
    gulp.src(`${basePath}/assets/**/*`).pipe(gulp.dest(`${basePath}/assets`));
    gulp.src(`${basePath}/styles/styles.min.css`).pipe(gulp.dest(`${basePath}/styles`));
    gulp.src(`${basePath}/scripts/scripts.min.js`).pipe(gulp.dest(`${basePath}/scripts`));
    gulp.src(`${basePath}/scripts/vendors/*`).pipe(gulp.dest(`${basePath}/scripts/vendors`));
    gulp.src(`${basePath}/*.html`).pipe(gulp.dest(`${basePath}/dist/`));
});

/**
 * @name Server task
 */
gulp.task("server", ["sass", "watchify", "preprocess", "sprity", "browser-sync"], () => {
    livereload.listen();
    gulp.start("watch");
});

/**
 * @name No server task
 */
gulp.task("static", ["sass", "watchify", "sprity", "browser-sync"], () => {
    livereload.listen();
    gulp.start("watch");
});

/**
 * @name Progress task
 */
gulp.task("progress", () => {
    if (args.html) {
        gulp.start("server");
    } else {
        gulp.start("static");
    }
});

/**
 * @name Develop task
 */
gulp.task("develop", () => {
    gulp.start("build");
});

/**
 * @name Build task
 */
gulp.task("build", () => {
    process.env.NODE_ENV = "production";

    if (args.dist) {
        runSequence("sass", "browserify", "preprocess", "sprity", "imagemin", "copy");
    } else {
        runSequence("sass", "browserify", "preprocess", "sprity", "imagemin");
    }
});

/**
 * @name Default task
 */
gulp.task("default", () => {
    if (args.production) {
        environment = "build";
    }
    else if (args.stage) {
        environment = "develop";
    }
    else {
        environment = "progress";
    }

    if (typeof args.path === "string") {
        basePath = `${basePath}/${args.path}`;
    }
    else {
        basePath = `${basePath}/www`;
    }

    prepareFiles();
    gulp.start(environment);
});
