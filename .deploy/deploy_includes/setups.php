<?php
namespace Deployer;

#------------------------------------------------------------------------------------
# SETUP TASKS & .DIST MOVES
#------------------------------------------------------------------------------------

/**
 * Setup .htaccess
 */
task('setups:assign_dists', function () {
    cd('{{release_path}}/.deploy');
    run('php VariablesSetter.php ' . get('deployed_folder'));
})->desc('Assign variables to .dist files');

/**
 * Setup .htaccess
 */
task('setups:htaccess', function () {
    run('cp {{release_path}}/.htaccess.dist {{release_path}}/.htaccess');
})->desc('Setup .htaccess.dist');

/**
 * Sets up parameterss.neon for DB connection
 */
task('setups:params-neon', function () {
    run("cp {{release_path}}/app/config/parameters.neon.dist {{release_path}}/app/config/parameters.neon");
})->desc('Setup parameters.neon.dist');

/**
 * Sets up config.js for building with gulp
 */
task('setups:config-js', function () {
    run("cp {{release_path}}/config.js.dist {{release_path}}/config.js");
})->desc('Setup confg.js.dist');

/**
 * Project custom configs
 */
task('setups', [
    'setups:assign_dists',
    'setups:htaccess',
    'setups:params-neon',
    'setups:config-js',
])->desc('Setup custom .dist configs and move them');