<?php
declare(strict_types=1);

// let bootstrap create Dependency Injection container
$container = require __DIR__ . '/../app/bootstrap.php';

// run application
$container->getByType(Nette\Application\Application::class)
	->run();
