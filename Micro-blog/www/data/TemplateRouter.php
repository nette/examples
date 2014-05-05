<?php

use Nette\Application\Routers;


/**
 * Micro-framework router for templates using {url} macro.
 */
class TemplateRouter extends Routers\RouteList
{

	public function __construct($path, $cachePath)
	{
		if (is_file($cacheFile = $cachePath . '/routes.php')) {
			$routes = require $cacheFile;
		} else {
			$routes = $this->scanRoutes($path);
			file_put_contents($cacheFile, '<?php return ' . var_export($routes, TRUE) . ';');
		}

		foreach ($routes as $mask => $file) {
			$this[] = new Routers\Route($mask, function($presenter) use ($file, $cachePath) {
				return $presenter->createTemplate(NULL, function() use ($cachePath) {
					$latte = new Latte\Engine;
					$latte->setTempDirectory($cachePath . '/cache');
					$macroSet = new Latte\Macros\MacroSet($latte->getCompiler());
					$macroSet->addMacro('url', function(){}); // ignore
					return $latte;
				})->setFile($file);
			});
		}
	}


	public function scanRoutes($path)
	{
		$routes = array();
		$latte = new Latte\Engine;
		$macroSet = new Latte\Macros\MacroSet($latte->getCompiler());
		$macroSet->addMacro('url', function($node) use (&$routes, &$file) {
			$routes[$node->args] = (string) $file;
		});
		foreach (Nette\Utils\Finder::findFiles('*.latte')->from($path) as $file) {
			$latte->compile($file);
		}
		return $routes;
	}

}
