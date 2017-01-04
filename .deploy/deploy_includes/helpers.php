<?php
namespace Deployer;

#------------------------------------------------------------------------------------
# HELPER TASKS
#------------------------------------------------------------------------------------

/**
 * Gather repository credentials for cloning
 */
task('helpers:repo-credentials', function () {
    $repoSetupComplete = false;
    $repo              = get('repository');
    $credsFile         = get('credentialsFile');

    $username = null;
    $password = null;

    if ($credsFile && file_exists($credsFile)) {
        $credentials = json_decode(file_get_contents($credsFile), true);
        if (count($credentials)) {
            foreach ($credentials as $host => $credential) {
                if (strpos($repo, $host)) {
                    writeln("Using stored credentials for host \"$host\"");
                    $username = $credential['username'];
                    $password = array_key_exists('password', $credential) ? $credential['password'] : askHiddenResponse("Password for username $username: ");

                    $repoSetupComplete = true;
                    break;
                }
            }
        }
    }

    if (!$repoSetupComplete) {
        writeln(sprintf('Please provide credentials for repository %s', $repo));
        $username = ask('Username: ', 'username');
        $password = askHiddenResponse('Password: ');
    }
    set('repository', str_replace('https://', 'https://' . urlencode($username) . ':' . urlencode($password) . '@', $repo));
})->desc('Sets up credentials for repository');

/**
 * Fix owner of project
 */
task('helpers:owner-fix', function () {
    $httpUser  = get("http_user", false);
    $httpGroup = get("http_group", false);

    if ($httpUser === false) {
        // Detect http user in process list.
        $httpUser = run("ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\\  -f1")->toString();

        if (empty($httpUser)) {
            throw new \RuntimeException(
                "Can't detect http user name.\n" .
                "Please setup `http_user` config parameter."
            );
        }
    }

    if ($httpUser === false) {
        throw new \RuntimeException("Please setup `http_group` config parameter.");
    }

    run(sprintf('sudo chown %s:%s {{deploy_path}} -R', $httpUser, $httpGroup));
})->desc('Fixing owner of directories');

/**
 * Cleanup files
 */
task('helpers:cleanup-files', function () {
    $files = get('cleanup_files');

    foreach ($files as $file) {
        run("rm -rf {{release_path}}/$file");
    }
})->desc('Cleaning files that are not used by app');

/**
 * Fix cleanup permissions
 */
task('helpers:fix-cleanup-permissions', function () {
    $releases = get('releases_list');

    $keep = get('keep_releases');

    while ($keep > 0) {
        array_shift($releases);
        --$keep;
    }

    foreach ($releases as $release) {
        run("sudo chmod 775 -R {{deploy_path}}/releases/$release");
    }
})->desc('Fixing cleanup permissions');

/**
 * Fix rollback permissions
 */
task('helpers:fix-rollback-permissions', function () {
    $releases = get('releases_list');

    if (isset($releases[1])) {
        run("sudo chmod 775 -R {{deploy_path}}/releases/$releases[0]");

        if (isVerbose()) {
            writeln("Rights to `{$releases[0]}` has been fixed. Directory now can be deleted.");
        }
    } else {
        writeln("<comment>No more releases you can revert to.</comment>");
    }
})->desc('Fixing cleanup permissions');

/**
 * Setup local variables for deploy
 */
task('helpers:set-local-variables', function () {

    $localDeployPath = get('local_deploy_path');
    set('deploy_path',$localDeployPath);

})->desc('Setup local variables for deploy');



