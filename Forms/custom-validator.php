<?php

/**
 * Nette\Forms custom validator example.
 */


require __DIR__ . '/../../Nette/loader.php';

use Nette\Forms\Form,
	Nette\Diagnostics\Debugger;

Debugger::enable();


// Define custom validator
class MyValidators
{
	static function divisibilityValidator($item, $arg)
	{
		return $item->value % $arg === 0;
	}
}


$form = new Form;

$form->addText('num1', 'Multiple of 8:')
	->addRule('MyValidators::divisibilityValidator', 'First number must be %d multiple', 8);

$form->addText('num2', 'Not multiple of 5:')
	->addRule(~'MyValidators::divisibilityValidator', 'Second number must not be %d multiple', 5); // negative

$form->addSubmit('submit', 'Send');


if ($form->isSuccess()) {
	echo '<h2>Form was submitted and successfully validated</h2>';
	Debugger::dump($form->values);
	exit; // here is usually redirect to another page
}

$form->setDefaults(array(
	'num1'    => '5',
	'num2'    => '5',
));


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Nette\Forms custom validator example | Nette Framework</title>
	<link rel="stylesheet" media="screen" href="files/style.css" />
	<script src="http://nette.github.com/resources/js/netteForms.js"></script>
	<script>
		Nette.validators.MyValidators_divisibilityValidator = function(elem, args, val) {
			return val % args === 0;
		};
	</script>
</head>

<body>
	<h1>Nette\Forms custom validator example</h1>

	<?php echo $form ?>
</body>
</html>
