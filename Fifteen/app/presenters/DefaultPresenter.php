<?php
declare(strict_types=1);


class DefaultPresenter extends Nette\Application\UI\Presenter
{
	public function renderDefault()
	{
		$this->redrawControl('round');
	}


	/**
	 * Fifteen game control factory.
	 * @return FifteenControl
	 */
	protected function createComponentFifteen()
	{
		$fifteen = new FifteenControl;
		$fifteen->onGameOver[] = [$this, 'gameOver'];
		$fifteen->redrawControl();
		return $fifteen;
	}


	public function gameOver($sender, $round)
	{
		$this->template->flash = 'Congratulations!';
		$this->redrawControl('flash');
	}
}
