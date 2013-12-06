<?php

/**
 * Nette\Forms custom control example.
 */


require __DIR__ . '/../../Nette/loader.php';

use Nette\Forms\Form,
	Nette\Utils\Html;


class DateInput extends Nette\Forms\Controls\BaseControl
{
	private $day, $month, $year;


	public function __construct($label = NULL)
	{
		parent::__construct($label);
		$this->addRule(__CLASS__ . '::validateDate', 'Date is invalid.');
	}


	public function setValue($value)
	{
		if ($value) {
			$date = Nette\DateTime::from($value);
			$this->day = $date->format('j');
			$this->month = $date->format('n');
			$this->year = $date->format('Y');
		} else {
			$this->day = $this->month = $this->year = NULL;
		}
	}


	/**
	 * @return DateTime|NULL
	 */
	public function getValue()
	{
		return self::validateDate($this)
			? date_create()->setDate($this->year, $this->month, $this->day)
			: NULL;
	}


	public function loadHttpData()
	{
		$this->day = $this->getHttpData(Form::DATA_LINE, '[day]');
		$this->month = $this->getHttpData(Form::DATA_LINE, '[month]');
		$this->year = $this->getHttpData(Form::DATA_LINE, '[year]');
	}


	/**
	 * Generates control's HTML element.
	 */
	public function getControl()
	{
		$name = $this->getHtmlName();
		return Html::el('input')->name($name . '[day]')->id($this->getHtmlId())->value($this->day)

			. Nette\Forms\Helpers::createSelectBox(
				array(1 => 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12),
				array('selected?' => $this->month)
				)->name($name . '[month]')

			. Html::el('input')->name($name . '[year]')->value($this->year);
	}


	/**
	 * @return bool
	 */
	public static function validateDate(Nette\Forms\IControl $control)
	{
		return checkdate($control->month, $control->day, $control->year);
	}

}


Nette\Diagnostics\Debugger::enable();

$form = new Form;

$form['date'] = new DateInput('Date:');
$form['date']->setDefaultValue(new DateTime);

$form->addSubmit('submit', 'Send');


if ($form->isSuccess()) {
	echo '<h2>Form was submitted and successfully validated</h2>';
	Nette\Diagnostics\Dumper::dump($form->getValues());
	exit;
}


?>
<!DOCTYPE html>
<meta charset="utf-8">
<title>Nette\Forms custom control example | Nette Framework</title>
<link rel="stylesheet" media="screen" href="assets/style.css" />

<h1>Nette\Forms custom control example</h1>

<?php echo $form ?>

<footer><a href="http://doc.nette.org/en/forms">see documentation</a></footer>
