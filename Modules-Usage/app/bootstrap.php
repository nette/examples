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
$configurator->setCacheDirectory(__DIR__ . '/../temp');

// Enable RobotLoader - this will load all classes automatically
$configurator->createRobotLoader()
	->addDirectory(__DIR__)
	->register();

// Create Dependency Injection container from config.neon file
$container = $configurator->loadConfig(__DIR__ . '/config.neon');


// Setup router using mod_rewrite detection
if (function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules())) {
	$container->router = $router = new RouteList;
	$router[] = new Route('index.php', 'Front:Default:default', Route::ONE_WAY);

	$router[] = $adminRouter = new RouteList('Admin');
	$adminRouter[] = new Route('admin/<presenter>/<action>', 'Default:default');

	$router[] = $frontRouter = new RouteList('Front');
	$frontRouter[] = new Route('<presenter>/<action>[/<id>]', 'Default:default');

} else {
	$container->router = new SimpleRouter('Front:Default:default');
}


// Run the application!
$container->application->run();
