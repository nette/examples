<?php

use Nette\Diagnostics\Debugger,
	Nette\Application\Routers\Route,
	Nette\Application\Routers\RouteList,
	Nette\Application\Routers\SimpleRouter;


// Load Nette Framework
require __DIR__ . '/../../../Nette/loader.php';


// Enable Nette Debugger for error visualisation & logging
Debugger::$strictMode = TRUE;
Debugger::$logDirectory = __DIR__ . '/../log';
Debugger::enable();


// Configure application
$configurator = new Nette\Config\Configurator;
$configurator->setTempDirectory(__DIR__ . '/../temp');

// Enable RobotLoader - this will load all classes automatically
$configurator->createRobotLoader()
	->addDirectory(__DIR__)
	->register();

// Create Dependency Injection container from config.neon file
$configurator->addConfig(__DIR__ . '/config.neon');
$container = $configurator->createContainer();

// Opens already started session
if ($container->session->exists()) {
	$container->session->start();
}

// Setup router using mod_rewrite detection
if (function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules())) {
	$container->router = new RouteList;
	$container->router[] = new Route('index.php', 'Dashboard:default', Route::ONE_WAY);
	$container->router[] = new Route('<presenter>/<action>[/<id>]', 'Dashboard:default');

} else {
	$container->router = new SimpleRouter('Dashboard:default');
}

// Run the application!
$container->application->run();
