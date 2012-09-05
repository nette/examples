<?php



class DefaultPresenter extends Nette\Application\UI\Presenter
{


	public function renderDefault()
	{
		$this->invalidateControl('round');
	}



	/**
	 * Fifteen game control factory.
	 * @return FifteenControl
	 */
	protected function createComponentFifteen()
	{
		$fifteen = new FifteenControl;
		$fifteen->onGameOver[] = $this->gameOver;
		$fifteen->invalidateControl();
		return $fifteen;
	}



	public function gameOver($sender, $round)
	{
		$this->template->flash = 'Congratulations!';
		$this->invalidateControl('flash');
	}

}
