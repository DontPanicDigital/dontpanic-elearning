<?php
namespace Deployer;

include_once __DIR__ . '/../../vendor/autoload.php';

/**
 * Get local username
 */

set('local_user', function () {
    return trim(run("whoami"));
});

// Do not skip slack notifications by default
set('slack_skip_notification', false);

desc('Notifying Slack channel of deployment');
task('slack:notify', function () {
    if (true === get('slack_skip_notification') || get('stages')[0] === 'local') {
        return;
    }

    global $php_errormsg;
    $config = (array) get('slack');

    if (!is_array($config) || !isset($config['token']) || !isset($config['team']) || !isset($config['channel'])) {
        throw new \RuntimeException("Please configure new slack: set('slack', ['token' => 'xoxp...', 'team' => 'team', 'channel' => '#channel', 'messsage' => 'message to send']);");
    }

    $server = \Deployer\Task\Context::get()->getServer();
    if ($server instanceof \Deployer\Server\Local) {
        $user = get('local_user');
    } else {
        $user = $server->getConfiguration()->getUser() ?: null;
    }

    $messagePlaceHolders = [
        '{{release_path}}' => get('release_path'),
        '{{host}}'         => get('server.host'),
        '{{server_name}}'  => get('server.name'),
        '{{stage}}'        => get('stages')[0],
        '{{user}}'         => $user,
        '{{branch}}'       => get('branch'),
        '{{app_name}}'     => isset($config['app']) ? $config['app'] : 'app-name',
    ];

    $urlParams = [
        'channel'  => $config['channel'],
        'token'    => $config['token'],
        'username' => $config['username'],
        'pretty'   => true,
    ];

    if (array_key_exists('attachments', $config)) {

        if (count($config['attachments'])) {
            $config['attachments'][0]['fallback'] = strtr('Deploy of application {{app_name}} successfully processed', $messagePlaceHolders);
        }

        $color = "warning";
        switch (get('stages')[0]) {
            case "production":
                $color = "danger";
                break;
            case "staging":
                $color = "good";
                break;
            case "local":
                $color = "#764FA5";
                break;
        }

        $config['attachments'][] = [
            "color"  => $color,
            "fields" => [
                [
                    "title" => "Name",
                    "value" => strtr('{{app_name}}', $messagePlaceHolders),
                    "short" => true,
                ],
                [
                    "title" => "Stage",
                    "value" => strtr('{{stage}}', $messagePlaceHolders),
                    "short" => true,
                ],
                [
                    "title" => "Server",
                    "value" => strtr('{{server_name}}', $messagePlaceHolders),
                    "short" => true,
                ],
                [
                    "title" => "Branch",
                    "value" => strtr('{{branch}}', $messagePlaceHolders),
                    "short" => true,
                ],
            ],
        ];

        $urlParams['attachments'] = json_encode($config['attachments']);
    }

    if (isset($config['icon_url'])) {
        unset($urlParams['icon_emoji']);
        $urlParams['icon_url'] = $config['icon_url'];
    }

    $url    = 'https://slack.com/api/chat.postMessage?' . http_build_query($urlParams);
    $result = @file_get_contents($url);

    if (!$result) {
        throw new \RuntimeException($php_errormsg);
    }
});
