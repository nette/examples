<?php

use Nette\Diagnostics\Debugger,
	Nette\Application\Routers\SimpleRouter;


// Load Nette Framework
require __DIR__ . '/../../../Nette/loader.php';


// Enable Nette Debugger for error visualisation & logging
Debugger::$strictMode = TRUE;
Debugger::$logDirectory = __DIR__ . '/../log';
Debugger::enable();


// Load default configuration
$configurator = new Nette\Configurator;
$configurator->container->params += $params;
$configurator->container->params['tempDir'] = __DIR__ . '/../temp';
$container = $configurator->container;

// Enable RobotLoader - this allows load all classes automatically
// so that you don't have to litter your code with 'require' statements
$container->robotLoader->register();


// Configure application
$container->router = new SimpleRouter('Default:default');


// Run the application!
$container->application->run();
