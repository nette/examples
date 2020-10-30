<?php

declare(strict_types=1);

namespace DemoApp\Module\Base\Presenters;

use Nette;


abstract class BasePresenter extends Nette\Application\UI\Presenter
{
	protected function beforeRender(): void
	{
		$this->template->viewName = $this->getView();
		$this->template->root = isset($_SERVER['SCRIPT_FILENAME'])
			? realpath(dirname($_SERVER['SCRIPT_FILENAME'], 2))
			: null;

		$a = strrpos($this->getName(), ':');
		if ($a === false) {
			$this->template->moduleName = '';
			$this->template->presenterName = $this->getName();
		} else {
			$this->template->moduleName = substr($this->getName(), 0, $a + 1);
			$this->template->presenterName = substr($this->getName(), $a + 1);
		}
	}
}
