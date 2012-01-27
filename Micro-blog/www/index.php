<?php

// Nette Framework Microblog example


// Load Nette Framework
require __DIR__ . '/../../../Nette/loader.php';
require __DIR__ . '/data/TemplateRouter.php';


// Configure application
$configurator = new Nette\Config\Configurator;
$configurator->setTempDirectory(__DIR__ . '/data/temp');

// Enable Nette Debugger for error visualisation & logging
$configurator->enableDebugger(__DIR__ . '/data/log');

// Create Dependency Injection container
$container = $configurator->createContainer();

// Enable template router
$container->router = new TemplateRouter('data/templates', __DIR__ . '/data/temp');

// Add access to database
$container->addService('database', function() {
	return new Nette\Database\Connection('sqlite2:data/blog.sdb');
});

// Run the application!
$container->application->run();
