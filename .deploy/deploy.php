<?php
namespace Deployer;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/deployer/deployer/recipe/common.php';
require __DIR__ . '/../vendor/deployer/recipes/local.php';

require_once __DIR__ . '/deploy_config.php';
require_once __DIR__ . '/deploy_includes/servers.php';
require_once __DIR__ . '/deploy_includes/slack.php';

#------------------------------------------------------------------------------------
# TASKS
#------------------------------------------------------------------------------------

/**
 * REMOTE DEPLOY
 */
task('deploy', [
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:writable',
    'deploy:clear_paths',
    'deploy:vendors',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
])->desc('Deploy your project');

after('deploy', 'success');

/**
 * LOCAL DEPLOY
 */
task('local', [
    'local:prepare',
    'local:release',
    'local:update_code',
    'local:vendors',
    'local:symlink',
    'local:current',
    'local:cleanup',
])->desc('Deploy your project');

require_once __DIR__ . '/deploy_includes/helpers.php';
before('deploy:update_code', 'helpers:repo-credentials');
before('deploy:symlink', 'helpers:owner-fix');
before('deploy:symlink', 'helpers:cleanup-files');
before('cleanup', 'helpers:fix-cleanup-permissions');

before('local', 'helpers:set-local-variables');
before('local:update_code', 'helpers:repo-credentials');
before('local:symlink', 'helpers:cleanup-files');

before('rollback', 'helpers:fix-rollback-permissions');

require_once __DIR__ . '/deploy_includes/database.php';
before('deploy:symlink', 'database:migrate');
before('local:symlink', 'database:migrate');

require_once __DIR__ . '/deploy_includes/setups.php';
after('deploy:vendors', 'setups');
after('local:vendors', 'setups');

require_once __DIR__ . '/deploy_includes/vendors.php';
after('deploy:vendors', 'vendors');
after('local:vendors', 'vendors');

after('local', 'slack:notify');
after('deploy', 'slack:notify');



