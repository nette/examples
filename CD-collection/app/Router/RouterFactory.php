<?php

declare(strict_types=1);

namespace App\Router;

use Nette;
use Nette\Application\IRouter;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\SimpleRouter;


final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): IRouter
	{
		// Setup router using mod_rewrite detection
		if (function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules(), true)) {
			$router = new RouteList;
			$router->addRoute('index.php', 'Dashboard:default', Route::ONE_WAY);
			$router->addRoute('<presenter>/<action>[/<id>]', 'Dashboard:default');
			return $router;
		}

		return new SimpleRouter('Dashboard:default');
	}
}
