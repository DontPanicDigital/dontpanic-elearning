<?php

namespace Deployer;

#------------------------------------------------------------------------------------
# VENDOR RELATED TASKS
#------------------------------------------------------------------------------------

/**
 * Install NPM dependencies
 */
task('vendors:npm', function () {
    if (get('use_npm')) {
        cd('{{release_path}}/');
        if (file_exists(PROJECT_HOME . 'package.json'))
            run('npm install');
    }
})->desc('Install NPM dependencies');

/**
 * Install bower dependencies
 */
task('vendors:bower', function () {
    if (get('use_bower')) {
        cd('{{release_path}}/');
        if (file_exists(PROJECT_HOME . 'bower.json'))
            run('bower install');
    }
})->desc('Install Bower dependencies');

/**
 * Build styles and js via gulp
 */
task('vendors:static-build', function () {
    if (get('use_npm') || get('use_static_build')) {
        cd('{{release_path}}/');

        if (file_exists(PROJECT_HOME . 'gulpfile.babel.js')) {
            if (get('server.name') == 'production') {
                run('sudo gulp --production');
            } else {
                run('sudo gulp --stage');
            }
        }
    }
})->desc('Build styles and javascript');

/**
 * Installs additional vendors
 */
task('vendors', [
    'vendors:npm',
    'vendors:bower',
    'vendors:static-build',
])->desc('Load packages from additional vendors and perform static build');
