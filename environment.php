<?php
if (function_exists('apache_getenv')) {
    switch (apache_getenv('ENVIRONMENT')) {
        case 'local':
            define('ENVIRONMENT', 'dev');
            break;
        case 'dev':
            define('ENVIRONMENT', 'dev');
            break;
        default:
            define('ENVIRONMENT', 'prod');
    }
} elseif (in_array('--debug-mode=on', $_SERVER['argv'], true)) {
    define('ENVIRONMENT', 'dev');
} else {
    define('ENVIRONMENT', 'prod');
}