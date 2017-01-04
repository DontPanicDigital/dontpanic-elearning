<?php
require __DIR__ . '/../environment.php';
require __DIR__ . '/../app/bootstrap.php';

$container = new Application(Application::MODULE_WEB);
$container->get_Container()->getByType('Nette\Application\Application')->run();