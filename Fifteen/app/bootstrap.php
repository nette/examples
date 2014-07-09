<?php

use Nette\Application\Routers\SimpleRouter;


// Load Nette Framework
if (@!include __DIR__ . '/../vendor/autoload.php') {
	die('Install Nette using `composer update`');
}

// Configure application
$configurator = new Nette\Configurator;

// Enable Nette Debugger for error visualisation & logging
$configurator->enableDebugger(__DIR__ . '/../log');

// Enable RobotLoader - this will load all classes automatically
$configurator->setTempDirectory(__DIR__ . '/../temp');
$configurator->createRobotLoader()
	->addDirectory(__DIR__)
	->register();

// Create default Dependency Injection container
$container = $configurator->createContainer();

// Setup router
$container->addService('router', new SimpleRouter('Default:default'));

return $container;
