<?php

/**
 * Nette\Forms naming containers example.
 *
 * - using naming containers
 */


require __DIR__ . '/../../Nette/loader.php';

use Nette\Forms\Form,
	Nette\Diagnostics\Debugger;

Debugger::enable();


$countries = array(
	'Select your country',
	'Europe' => array(
		'CZ' => 'Czech Republic',
		'SK' => 'Slovakia',
		'GB' => 'United Kingdom',
	),
	'CA' => 'Canada',
	'US' => 'United States',
	'?'  => 'other',
);

$sex = array(
	'm' => 'male',
	'f' => 'female',
);



// Define form with validation rules
$form = new Form;

// group First person
$form->addGroup('First person');
$first = $form->addContainer('first');
$first->addText('name', 'Your name:');
$first->addText('email', 'Email:');
$first->addText('street', 'Street:');
$first->addText('city', 'City:');
$first->addSelect('country', 'Country:', $countries);

// group Second person
$form->addGroup('Second person');
$second = $form->addContainer('second');
$second->addText('name', 'Your name:');
$second->addText('email', 'Email:');
$second->addText('street', 'Street:');
$second->addText('city', 'City:');
$second->addSelect('country', 'Country:', $countries);

// group for buttons
$form->addGroup();

$form->addSubmit('submit', 'Send');



// Check if form was successfully submitted?
if ($form->isSubmitted() && $form->isValid()) {
	echo '<h2>Form was submitted and successfully validated</h2>';

	Debugger::dump($form->values);

	exit; // here is usually redirect to another page
}



// Render form
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">

	<title>Nette\Forms naming containers example | Nette Framework</title>

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
	<link rel="stylesheet" type="text/css" media="screen" href="files/style.css" />
	</style>
</head>

<body>
	<h1>Nette\Forms naming containers example</h1>

	<?php echo $form ?>
</body>
</html>
