<?php

declare(strict_types=1);

namespace App;

use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;
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

		// Create Dependency Injection container from config.neon file
		$configurator->addConfig(__DIR__ . '/config/common.neon');

		// Setup router using mod_rewrite detection
		if (function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules(), true)) {
			$router = new RouteList;
			$router->addRoute('index.php', 'Front:Default:default', Route::ONE_WAY);

			$router->withModule('Admin')
				->addRoute('admin/<presenter>/<action>', 'Default:default');

			$router->withModule('Front')
				->addRoute('<presenter>/<action>[/<id>]', 'Default:default');

		} else {
			$router = new SimpleRouter('Front:Default:default');
		}
		$configurator->addServices(['router' => $router]);

		return $configurator;
	}
}
