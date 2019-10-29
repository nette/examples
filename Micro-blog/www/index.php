<?php

declare(strict_types=1);

// Nette Framework Microblog example


// Load Nette Framework
if (@!include __DIR__ . '/data/vendor/autoload.php') {
	die('Install Nette using `composer update`');
}
require __DIR__ . '/data/TemplateRouter.php';

// Configure application
$configurator = new Nette\Configurator;

// Enable Tracy for error visualisation & logging
$configurator->enableTracy(__DIR__ . '/data/log');

// Create Dependency Injection container
$configurator->setTempDirectory(__DIR__ . '/data/temp');
$configurator->addConfig(__DIR__ . '/data/config.neon');
$container = $configurator->createContainer();

// Enable template router
$container->addService('router', new TemplateRouter('data/templates', __DIR__ . '/data/temp'));

// Run the application!
$container->getByType(Nette\Application\Application::class)
	->run();
