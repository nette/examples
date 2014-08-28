<?php

use Nette\Application\Routers,
	Nette\Latte;


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
			$routes = array();
			foreach (Nette\Utils\Finder::findFiles('*.latte')->from($path) as $file) {
				$latte = new Latte\Engine;
				$macroSet = new Latte\Macros\MacroSet($latte->compiler);
				$macroSet->addMacro('url', function($node) use (&$routes, $file) {
					$routes[$node->args] = (string) $file;
				});
				$latte->__invoke(file_get_contents($file));
			}
			file_put_contents($cacheFile, '<?php return ' . var_export($routes, TRUE) . ';');
		}

		foreach ($routes as $mask => $file) {
			$this[] = new Routers\Route($mask, function($presenter) use ($file) {
				return $presenter->createTemplate(NULL, function() {
					$latte = new Nette\Latte\Engine;
					$macroSet = new Latte\Macros\MacroSet($latte->compiler);
					$macroSet->addMacro('url', function() {});
					return $latte;
				})->setFile($file);
			});
		}
	}

}
