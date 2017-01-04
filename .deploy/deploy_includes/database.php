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
        run('{{bin/php}} migrations:migrate --allow-no-migration');
    }
})->desc('Migrate database');