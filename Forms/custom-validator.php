<?php

/**
 * Nette\Forms custom validator example.
 */


require __DIR__ . '/../../Nette/loader.php';

use Nette\Forms\Form,
	Nette\Diagnostics\Debugger;

Debugger::enable();



// Define custom validator
function divisibilityValidator($item, $arg)
{
	return $item->value % $arg === 0;
}



// Define form with validation rules
$form = new Form;

$form->addText('num1', 'Multiple of 8:')
	->addRule('divisibilityValidator', 'First number must be %d multiple', 8);

$form->addText('num2', 'Not multiple of 5:')
	->addRule(~'divisibilityValidator', 'Second number must not be %d multiple', 5); // negative


$form->addSubmit('submit', 'Send');



// Check if form was submitted?
if ($form->isSubmitted()) {

	// Check if form is valid
	if ($form->isValid()) {
		echo '<h2>Form was submitted and successfully validated</h2>';

		Debugger::dump($form->values);

		exit; // here is usually redirect to another page
	}

} else {
	// not submitted, define default values
	$defaults = array(
		'num1'    => '5',
		'num2'    => '5',
	);

	$form->setDefaults($defaults);
}



// Render form
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">

	<title>Nette\Forms custom validator example | Nette Framework</title>

	<style type="text/css">
	.required {
		color: maroon
	}

	fieldset {
		padding: .5em;
		margin: .5em 0;
		background: #E4F1FC;
		border: 1px solid #B2D1EB;
	}

	input.button {
		font-size: 120%;
	}

	th {
		width: 10em;
		text-align: right;
	}
	</style>
	<link rel="stylesheet" type="text/css" media="screen" href="files/style.css" />
	<script src="http://nette.github.com/resources/js/netteForms.js"></script>
	<script>
		Nette.validators.divisibilityValidator = function(elem, args, val) {
			return val % args === 0;
		};
	</script>
</head>

<body>
	<h1>Nette\Forms custom validator example</h1>

	<?php echo $form ?>
</body>
</html>
