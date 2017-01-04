<?php
require __DIR__ . '/../vendor/autoload.php';

use Nette\Application\Routers\Route;
use Tracy\Debugger;

function db($var, $title = null)
{
    Debugger::barDump($var, $title);
}

define('APP_DIR', __DIR__);
define('BASE_DIR', __DIR__ . '/..');
define('TEMP_DIR', BASE_DIR . '/temp');
define('CACHE_DIR', TEMP_DIR . '/' . ENVIRONMENT . '/cache');
define('EMAILS_DIR', APP_DIR . '/templates/emails');
define('PDF_DIR', APP_DIR . '/templates/pdf');

class Application
{

    const MODULE_WEB = 'web';
    const MODULE_API = 'api';

    /** @var string|null */
    private $module;

    public function __construct($module = null)
    {
        $this->module = $module;
    }

    private function get_Configurator()
    {
        /** @var \Nette\Configurator */
        $configurator = new Nette\Configurator;

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            Route::$defaultFlags |= Route::SECURED;
        }

        if (ENVIRONMENT === 'prod' && Debugger::detectDebugMode()) {
            throw new Exception('Cannot run app in Production Mode when not on production server');
        }

        Debugger::$logDirectory = BASE_DIR . '/log';
        Debugger::$strictMode   = true;
        Debugger::$email        = 'zvitek@iwory.cz';
        //Debugger::$errorTemplate = APP_DIR . '/templates/Error/500.html';
        if (ENVIRONMENT === 'prod') {
            Debugger::enable(Debugger::PRODUCTION);
        } else {
            Debugger::enable();
        }

        $configurator->enableDebugger(__DIR__ . '/../log');
        $configurator->setTempDirectory(__DIR__ . '/../temp/' . ENVIRONMENT);

        $configurator->createRobotLoader()
                     ->addDirectory(__DIR__)
                     ->register();

        $configurator->addConfig(__DIR__ . '/config/prod.neon');
        $configurator->addConfig(__DIR__ . '/config/services/basic.neon');
        $configurator->addConfig(__DIR__ . '/config/services/components.neon');
        $configurator->addConfig(__DIR__ . '/config/services/models.neon');
        $configurator->addConfig(__DIR__ . '/config/services/facades.neon');
        $configurator->addConfig(__DIR__ . '/config/services/http.neon');

        $this->module !== self::MODULE_WEB ?: $configurator->addConfig(__DIR__ . '/config/module/web.neon');

        switch (ENVIRONMENT) {
            case 'dev':
                $configurator->addConfig(__DIR__ . '/config/dev.neon');
                if (Debugger::detectDebugMode()) {
                    $configurator->addConfig(__DIR__ . '/config/local.neon');
                }
        }

        $configurator->addConfig(__DIR__ . '/config/parameters.neon');

        return $configurator;
    }

    public function get_Container()
    {
        $container = $this->get_Configurator()->createContainer();

        return $container;
    }
}

