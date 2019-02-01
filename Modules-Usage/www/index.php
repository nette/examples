<?php

declare(strict_types=1);

if (@!include __DIR__ . '/../vendor/autoload.php') {
	die('Install Nette using `composer update`');
}

App\Booting::boot()
	->createContainer()
	->getByType(Nette\Application\Application::class)
	->run();
