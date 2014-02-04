<?php

namespace DemoApp\Presenters;

use Nette;


abstract class BasePresenter extends Nette\Application\UI\Presenter
{

	protected function beforeRender()
	{
		$this->template->viewName = $this->view;
		$this->template->root = isset($_SERVER['SCRIPT_FILENAME']) ? realpath(dirname(dirname($_SERVER['SCRIPT_FILENAME']))) : NULL;

		$a = strrpos($this->name, ':');
		if ($a === FALSE) {
			$this->template->moduleName = '';
			$this->template->presenterName = $this->name;
		} else {
			$this->template->moduleName = substr($this->name, 0, $a + 1);
			$this->template->presenterName = substr($this->name, $a + 1);
		}
	}

}
