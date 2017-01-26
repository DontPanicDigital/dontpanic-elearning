<?php

namespace Deployer;

#------------------------------------------------------------------------------------
# DATABASE TASKS
#------------------------------------------------------------------------------------

/**
 * Migrate database
 */
task('database:migrate', function () {
    if (get('use_database_migration')) {
        cd('{{release_path}}/');
        run('{{bin/php}} www/index.php migrations:migrate --allow-no-migration');
    }
})->desc('Migrate database');