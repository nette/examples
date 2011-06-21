<?php

use Nette\Diagnostics\Debugger,
	Nette\Application\Routers\Route,
	Nette\Application\Routers\RouteList,
	Nette\Application\Routers\SimpleRouter;


// Load Nette Framework
// this allows load Nette Framework classes automatically so that
// you don't have to litter your code with 'require' statements
require __DIR__ . '/../../../Nette/loader.php';


// Enable Nette\Debug for error visualisation & logging
Debugger::enable();


// Load configuration from config.neon file
$configurator = new Nette\Configurator;
$container = $configurator->loadConfig(__DIR__ . '/config.neon');


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
