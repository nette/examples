<?php

use Nette\Application\UI;

/**
 * The Fifteen game control
 *
 * @author     David Grudl
 */
class FifteenControl extends UI\Control
{
	/** @var int */
	protected $width = 4;

	/** @var array of function ($sender) */
	public $onAfterClick;

	/** @var array of function ($sender, $round) */
	public $onGameOver;

	/** @persistent array */
	public $order = array();

	/** @persistent int */
	public $round = 0;


	public function __construct()
	{
		parent::__construct();
		$this->order = range(0, $this->width * $this->width - 1);
	}


	public function handleClick($x, $y)
	{
		if (!$this->isClickable($x, $y)) {
			throw new UI\BadSignalException('Action not allowed.');
		}

		$this->move($x, $y);
		$this->round++;
		$this->onAfterClick($this);

		if ($this->order == range(0, $this->width * $this->width - 1)) {
			$this->onGameOver($this, $this->round);
		}
	}


	public function handleShuffle()
	{
		$i = 100;
		while ($i) {
			$x = rand(0, $this->width - 1);
			$y = rand(0, $this->width - 1);
			if ($this->isClickable($x, $y)) {
				$this->move($x, $y);
				$i--;
			}
		}
		$this->round = 0;
	}


	public function getRound()
	{
		return $this->round;
	}


	public function isClickable($x, $y, & $rel = NULL)
	{
		$rel = NULL;
		$pos = $x + $y * $this->width;
		$empty = $this->searchEmpty();
		$y = (int) ($empty / $this->width);
		$x = $empty % $this->width;
		if ($x > 0 && $pos === $empty - 1) {
			$rel = '-1,';
			return TRUE;
		}
		if ($x < $this->width-1 && $pos === $empty + 1) {
			$rel = '+1,';
			return TRUE;
		}
		if ($y > 0 && $pos === $empty - $this->width) {
			$rel = ',-1';
			return TRUE;
		}
		if ($y < $this->width-1 && $pos === $empty + $this->width) {
			$rel = ',+1';
			return TRUE;
		}
		return FALSE;
	}


	private function move($x, $y)
	{
		$pos = $x + $y * $this->width;
		$emptyPos = $this->searchEmpty();
		$this->order[$emptyPos] = $this->order[$pos];
		$this->order[$pos] = 0;
	}


	private function searchEmpty()
	{
		return array_search(0, $this->order);
	}


	public function render()
	{
		$template = $this->template;
		$template->setFile(__DIR__ . '/FifteenControl.latte');
		$template->width = $this->width;
		$template->order = $this->order;
		$template->render();
	}


	/**
	 * Loads params
	 * @param  array
	 * @return void
	 */
	public function loadState(array $params)
	{
		if (isset($params['order'])) {
			$params['order'] = explode('.', (string) $params['order']);

			// validate
			$copy = $params['order'];
			sort($copy);
			if ($copy != range(0, $this->width * $this->width - 1)) {
				unset($params['order']);
			}
		}

		parent::loadState($params);
	}


	/**
	 * Save params
	 * @param  array
	 * @return void
	 */
	public function saveState(array & $params)
	{
		parent::saveState($params);
		if (isset($params['order'])) {
			$params['order'] = implode('.', $params['order']);
		}
	}

}
