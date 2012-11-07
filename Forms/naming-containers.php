<?php

/**
 * Nette\Forms naming containers example.
 */


require __DIR__ . '/../../Nette/loader.php';

use Nette\Forms\Form,
	Nette\Diagnostics\Debugger;

Debugger::enable();


$form = new Form;

// group First person
$form->addGroup('First person');

$first = $form->addContainer('first');
$first->addText('name', 'Your name:');
$first->addText('email', 'Email:');
$first->addText('street', 'Street:');
$first->addText('city', 'City:');
$first->addSelect('country', 'Country:', array(
	'Europe' => array(
		'CZ' => 'Czech Republic',
		'SK' => 'Slovakia',
	),
	'US' => 'USA',
	'?'  => 'other',
));

// group Second person
$form->addGroup('Second person');

$second = $form->addContainer('second');
$second->addText('name', 'Your name:');
$second->addText('email', 'Email:');
$second->addText('street', 'Street:');
$second->addText('city', 'City:');
$second->addSelect('country', 'Country:', array(
	'Europe',
	'USA',
));

// group for button
$form->addGroup();

$form->addSubmit('submit', 'Send');



if ($form->isSuccess()) {
	echo '<h2>Form was submitted and successfully validated</h2>';

	Debugger::dump($form->values);

	exit; // here is usually redirect to another page
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Nette\Forms naming containers example | Nette Framework</title>

	<style>
	.required label {
		font-weight: bold;
	}

	.error {
		color: #D00;
		font-weight: bold;
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
		font-weight: normal;
	}
	<link rel="stylesheet" media="screen" href="files/style.css" />
	</style>
</head>

<body>
	<h1>Nette\Forms naming containers example</h1>

	<?php echo $form ?>
</body>
</html>
