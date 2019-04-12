<?php

declare(strict_types=1);

namespace App;

use Nette\Application\Routers\SimpleRouter;
use Nette\Configurator;


class Bootstrap
{
	public static function boot(): Configurator
	{
		$configurator = new Configurator;

		// Enable Tracy for error visualisation & logging
		//$configurator->setDebugMode('23.75.345.200'); // enable for your remote IP
		$configurator->enableTracy(__DIR__ . '/../log');

		// Enable RobotLoader - this will load all classes automatically
		$configurator->setTempDirectory(__DIR__ . '/../temp');
		$configurator->createRobotLoader()
			->addDirectory(__DIR__)
			->register();

		// Setup router
		$configurator->addServices(['router' => new SimpleRouter('Default:default')]);

		return $configurator;
	}
}
