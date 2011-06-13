<?php

// Nette Framework Microblog example

use Nette\Diagnostics\Debugger;


// load libraries
require __DIR__ . '/../../../Nette/loader.php';
require __DIR__ . '/data/TemplateRouter.php';


// enable Debugger
Debugger::$logDirectory = __DIR__ . '/data/log';
Debugger::$strictMode = TRUE;
Debugger::enable();


// enable template router
$configurator = new Nette\Configurator;
$context = $configurator->container;
$context->params['tempDir'] = __DIR__ . '/data/temp';
$context->application->router[] = new TemplateRouter('data/templates');


// add access to database
$context->addService('database', function() {
	return new Nette\Database\Connection('sqlite2:data/blog.sdb');
});


// run the application!
$context->application->run();
