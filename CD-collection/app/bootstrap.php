<?php

declare(strict_types=1);

use Nette\Application\Routers\Route;
use Nette\Application\Routers\SimpleRouter;


// Load Nette Framework
if (@!include __DIR__ . '/../vendor/autoload.php') {
	die('Install Nette using `composer update`');
}

// Configure application
$configurator = new Nette\Configurator;

// Enable Tracy for error visualisation & logging
$configurator->enableTracy(__DIR__ . '/../log');

// Enable RobotLoader - this will load all classes automatically
$configurator->setTempDirectory(__DIR__ . '/../temp');
$configurator->createRobotLoader()
	->addDirectory(__DIR__)
	->register();

// Create Dependency Injection container from config.neon file
$configurator->addConfig(__DIR__ . '/config.neon');
$container = $configurator->createContainer();

// Setup router using mod_rewrite detection
if (function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules(), true)) {
	$router = $container->getByType(Nette\Application\IRouter::class);
	$router[] = new Route('index.php', 'Dashboard:default', Route::ONE_WAY);
	$router[] = new Route('<presenter>/<action>[/<id>]', 'Dashboard:default');

} else {
	$container->addService('router', new SimpleRouter('Dashboard:default'));
}

return $container;
