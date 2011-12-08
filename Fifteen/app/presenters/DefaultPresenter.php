<?php



class DefaultPresenter extends Nette\Application\UI\Presenter
{


	public function renderDefault()
	{
		$this->invalidateControl('round');
	}



	/**
	 * Fifteen game control factory.
	 * @return mixed
	 */
	protected function createComponentFifteen()
	{
		$fifteen = new FifteenControl;
		$fifteen->onGameOver[] = callback($this, 'gameOver');
		$fifteen->invalidateControl();
		return $fifteen;
	}



	public function gameOver($sender, $round)
	{
		$this->template->flash = 'Congratulations!';
		$this->invalidateControl('flash');
	}

}
