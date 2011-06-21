<?php

use Nette\Diagnostics\Debugger,
	Nette\Application\Routers\SimpleRouter;



// Load Nette Framework
// this allows load Nette Framework classes automatically so that
// you don't have to litter your code with 'require' statements
require __DIR__ . '/../../../Nette/loader.php';


// Enable Nette\Debug for error visualisation & logging
Debugger::enable();

// Enable RobotLoader - this allows load all classes automatically
// so that you don't have to litter your code with 'require' statements
$configurator = new Nette\Configurator;
$container = $configurator->container;
$container->robotLoader;


// Configure application
$container->router = new SimpleRouter('Default:default');


// Run the application!
$container->application->run();
