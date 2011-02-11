<?php

use Nette\Debug,
	Nette\Environment,
	Nette\Application\Route,
	Nette\Application\SimpleRouter;


// Load Nette Framework
// this allows load Nette Framework classes automatically so that
// you don't have to litter your code with 'require' statements
require __DIR__ . '/../../../Nette/loader.php';


// Enable Nette\Debug for error visualisation & logging
Debug::enable();


// Load configuration from config.neon file
Environment::loadConfig();


// Configure application
$application = Environment::getApplication();


// Establish database connection
$application->onStartup[] = function() {
	Model::initialize(Environment::getConfig()->database);
};


// Setup router
$application->onStartup[] = function() use ($application) {
	$router = $application->getRouter();

	// mod_rewrite detection
	if (function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules())) {
		$router[] = new Route('index.php', 'Dashboard:default', Route::ONE_WAY);

		$router[] = new Route('<presenter>/<action>[/<id>]', 'Dashboard:default');

	} else {
		$router[] = new SimpleRouter('Dashboard:default');
	}
};


// Run the application!
$application->run();
