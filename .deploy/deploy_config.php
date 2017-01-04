<?php
namespace Deployer;

#------------------------------------------------------------------------------------
# GLOBAL VARIABLES AND SWITCHES
#------------------------------------------------------------------------------------

define('PROJECT_HOME', __DIR__ . '/../');

//VENDORS SCRIPTS SWITCHES
set('use_npm', false);
set('use_bower', false);

// STATIC BUILD SWITCH
set('use_static_build', get('use_npm'));

//MIGRATE DATABASE SWITCH
set('use_database_migration', false);

//LOCAL DEPLOY SWITCH
set('use_local_deploy', true);

//SKIP NOTIFICATION VIA SLACK SWITCH
set('slack_skip_notification', false);

set('local_bin/composer', 'php /usr/local/bin/composer.phar');

#--------------------------------------------
# REQUIRED
#--------------------------------------------

/**
 * @required
 * APP NAME
 *
 * Sets the name of application
 */
set('app_name', 'sandbox');

/**
 * @required
 * REPOSITORY (https:// version)
 *
 * Sets the path to repository of the project, that is deployed
 */
set('repository', 'https://github.com/DontPanicDigital/sandbox.git');

/**
 * @required
 * DIRECTORY TO DEPLOY
 *
 * Name of directory on the server.
 * This directory have to be included in HOST config of webserver
 */
set('deployed_folder', "sandbox");

/**
 * @required
 * DEFAULT STAGE TO RUN
 *
 * Default stage to go with
 * Can be : produciton | staging | local
 */
set('default_stage', 'staging');

/**
 * @required
 * RELEASES KEPT
 *
 * Number of releases kept on server
 * Possibility to rollback to them
 */
set('keep_releases', 5);

#--------------------------------------------
# OPTIONAL
#--------------------------------------------

/**
 * @optional
 * SHARED FILES
 *
 * Shared files accross releases
 * Paths relative to deployed folder
 */
set('shared_files', []);

/**
 * @optional
 * SHARED DIRS
 *
 * Shared folders accross releases
 * Paths relative to deployed folder
 */
set('shared_dirs', [
    'log',
    'temp/data',
    'data',
]);

/**
 * @optional
 * WRITABLE DIRS
 *
 * Setup writable dirs
 * Paths relative do deployed folder
 * Dirs gets 777 chmod applied at them
 */
set('writable_dirs', [
    'temp/cache',
    'log',
]);

/**
 * @optional
 * CLEANUP FILES
 *
 * Files to be deleted after successfull deploy
 * Paths relative do deployed folder
 */
set('cleanup_files', [
    '.babelrc',
    '.eslintrc',
    '.gitignore',
    '.htaccess.dist',
    'composer.*',
    'config.js.dist',
    'database.sql',
    'gulpfile.babel.js',
    'package.json',
    'png.hbs',
    '.deploy',
    '.git',
    'README.md',
    'node_modules',
    'app/config/parameters.neon.dist',
    'www/scripts',
    'www/styles',
]);

/**
 * @optional
 * CLEAR PATHS
 *
 * Paths, that should be cleared after deploy
 * Paths relative to deployed folder
 */
set('clear_paths', []);

/**
 * @optional
 * SET CREADENTIALS FILE
 * This file constains a login data to repositories
 * Wildcards can be used
 *
 * @example Contents of .deployer_creds.json file
 * {
 *  "github.com" : {
 *    "username" : "MyUserName",
 *    "password" : "Str0ngP4$$W0rD"
 *  },
 *  "bitbucket.org/someUser/specificRepo" : {
 *    "username" : "MyUserName",
 *    "password" : "Str0ngP4$$W0rD"
 *  }
 *}
 */
set('credentialsFile', $_SERVER['HOME'] . '/.deployer_creds.json');

/**
 * @optional
 * HTTP USER
 *
 * Name of http user running web server on deployed server
 * Used to attach correct ownership
 */
set('http_user', 'www-data');

/**
 * @optional
 * HTTP GROUP
 *
 * Name of http group running web server on deployed server
 * Used to attach correct ownership
 */
set('http_group', 'www-data');

/**
 * @optional
 * LOCAL BRANCH
 *
 * Branch to clone from git repo when performing local deploy
 */
set('local_branch', 'develop');

/**
 * @optional
 * SLACK PARAMETERS
 *
 * Params used for massaging in slack
 */
set('slack', [
    'token'   => 'xoxp-17944573174-18186521328-122255265456-65c63c7334466d335c97d23237081a24',
//    'channel' => '#app_errors',
    'channel' => '#deploys',
    'team'    => 'dntp',

    'username' => 'Deployer',
    'icon_url' => 'https://deployer.org/images/deployer-sticker.png',

    'app'         => get('app_name'),
    //    'message' => "Deployment of application *{{app_name}}* to {{host}}({{server.name}}) was successful \n <!date^" . time() . "^{date_long_pretty} at {time}|->",
    'attachments' => [
        [
            'color' => 'good',
            'title' => 'Deploy successfull',
        ],
    ],
]);