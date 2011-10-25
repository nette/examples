<?php

// Nette Framework Microblog example

use Nette\Diagnostics\Debugger;


// load libraries
require __DIR__ . '/../../../Nette/loader.php';
require __DIR__ . '/data/TemplateRouter.php';


// enable Nette Debugger for error visualisation & logging
Debugger::$logDirectory = __DIR__ . '/data/log';
Debugger::$strictMode = TRUE;
Debugger::enable();


// configure application
$configurator = new Nette\Config\Configurator;
$configurator->setCacheDirectory(__DIR__ . '/data/temp');
$container = $configurator->getContainer();

// enable template router
$container->router = new TemplateRouter('data/templates');


// add access to database
$container->addService('database', function() {
	return new Nette\Database\Connection('sqlite2:data/blog.sdb');
});


// run the application!
$container->application->run();
