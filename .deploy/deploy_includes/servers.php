<?php
namespace Deployer;

#------------------------------------------------------------------------------------
# SERVERS CONFIGURATION
#------------------------------------------------------------------------------------

/**
 * PRODUCITON
 */
server('production', 'lisa.dontpanic.cz')
    ->user('digi')
    ->identityFile('~/.ssh/slave_digitalocean', '~/.ssh/slave_digitalocean')
    ->set('deploy_path', '/var/www/sites/' . get('deployed_folder'))
    ->set('branch', 'master')
    ->stage('production');

/**
 * STAGE
 */
server('stage', 'bart.dontpanic.cz')
    ->user('digi')
    ->identityFile('~/.ssh/slave_digitalocean', '~/.ssh/slave_digitalocean')
    ->set('deploy_path', '/var/www/sites/' . get('deployed_folder'))
    ->set('branch', 'develop')
    ->stage('staging');

/**
 * LOCAL
 */
localServer('local')
    ->user('digi')
    ->set('local_deploy_path', PROJECT_HOME . 'local_deploy')
    ->set('branch', get('local_branch', 'develop'))
    ->stage('local');

