<?php

// Nette Framework Microblog example


// Load Nette Framework
if (@!include __DIR__ . '/data/vendor/autoload.php') {
	die('Install Nette using `composer update`');
}
require __DIR__ . '/data/TemplateRouter.php';

// Configure application
date_default_timezone_set('Europe/Prague');
$configurator = new Nette\Config\Configurator;

// Enable Nette Debugger for error visualisation & logging
$configurator->enableDebugger(__DIR__ . '/data/log');

// Create Dependency Injection container
$configurator->setTempDirectory(__DIR__ . '/data/temp');
$container = $configurator->createContainer();

// Enable template router
$container->router = new TemplateRouter('data/templates', __DIR__ . '/data/temp');

// Add access to database
$container->addService('database', function() {
	return new Nette\Database\Connection('sqlite:data/blog.db3');
});

// Run the application!
$container->getService('application')->run();
