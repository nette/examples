<?php

use Nette\Debug,
	Nette\Environment,
	Nette\Application\Route,
	Nette\Application\MultiRouter,
	Nette\Application\SimpleRouter;



// Step 1: Load Nette Framework
// this allows load Nette Framework classes automatically so that
// you don't have to litter your code with 'require' statements
require LIBS_DIR . '/Nette/Nette/loader.php';



// Step 2: Configure environment
// 2a) enable Nette\Debug for better exception and error visualisation
Debug::enable();

// 2b) load configuration from config.ini file
Environment::loadConfig();



// Step 3: Configure application
$application = Environment::getApplication();



// Step 4: Setup application router
$router = $application->getRouter();

// mod_rewrite detection
if (function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules())) {
	$router[] = new Route('index.php', 'Front:Default:default', Route::ONE_WAY);

	$router[] = $adminRouter = new MultiRouter('Admin');
	$adminRouter[] = new Route('admin/<presenter>/<action>', 'Default:default');

	$router[] = $frontRouter = new MultiRouter('Front');
	$frontRouter[] = new Route('<presenter>/<action>[/<id>]', 'Default:default');

} else {
	$router[] = new SimpleRouter('Front:Default:default');
}



// Step 5: Run the application!
$application->run();
