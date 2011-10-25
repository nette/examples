<?php

use Nette\Diagnostics\Debugger,
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

// Create default Dependency Injection container
$container = $configurator->getContainer();


// Setup router
$container->router = new SimpleRouter('Default:default');


// Run the application!
$container->application->run();
