<?php
declare(strict_types=1);


class DefaultPresenter extends Nette\Application\UI\Presenter
{
	public function renderDefault(): void
	{
		$this->redrawControl('round');
	}


	/**
	 * Fifteen game control factory.
	 */
	protected function createComponentFifteen(): FifteenControl
	{
		$fifteen = new FifteenControl;
		$fifteen->onGameOver[] = [$this, 'gameOver'];
		$fifteen->redrawControl();
		return $fifteen;
	}


	public function gameOver($sender, int $round): void
	{
		$this->template->flash = 'Congratulations!';
		$this->redrawControl('flash');
	}
}
