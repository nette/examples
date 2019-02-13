<?php

declare(strict_types=1);

use Nette\Application\Routers;


/**
 * Micro-framework router for templates using {url} macro.
 */
class TemplateRouter extends Routers\RouteList
{
	public function __construct(string $path, string $cachePath)
	{
		if (is_file($cacheFile = $cachePath . '/routes.php')) {
			$routes = require $cacheFile;
		} else {
			$routes = $this->scanRoutes($path);
			file_put_contents($cacheFile, '<?php return ' . var_export($routes, true) . ';');
		}

		foreach ($routes as $mask => $file) {
			$this[] = new Routers\Route($mask, function (NetteModule\MicroPresenter $presenter) use ($file, $cachePath) {
				return $presenter->createTemplate(null, function () use ($cachePath): Latte\Engine {
					$latte = new Latte\Engine;
					$latte->setTempDirectory($cachePath . '/cache');
					$macroSet = new Latte\Macros\MacroSet($latte->getCompiler());
					$macroSet->addMacro('url', function () {}, null, null, $macroSet::ALLOWED_IN_HEAD); // ignore
					return $latte;
				})->setFile($file);
			});
		}
	}


	public function scanRoutes(string $path): array
	{
		$routes = [];
		$latte = new Latte\Engine;
		$macroSet = new Latte\Macros\MacroSet($latte->getCompiler());
		$macroSet->addMacro('url', function ($node) use (&$routes, &$file) {
			$routes[$node->args] = (string) $file;
		}, null, null, $macroSet::ALLOWED_IN_HEAD);
		foreach (Nette\Utils\Finder::findFiles('*.latte')->from($path) as $file) {
			$latte->compile((string) $file);
		}
		return $routes;
	}
}
