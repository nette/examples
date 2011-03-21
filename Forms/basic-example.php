<?php

/**
 * Nette\Forms basic example.
 *
 * - form definition using fluent interfaces
 * - form groups usage
 * - default rendering
 */


require __DIR__ . '/../../Nette/loader.php';

use Nette\Forms\Form,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html;

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



// Step 1: Define form with validation rules
$form = new Form;

// group Personal data
$form->addGroup('Personal data')
	->setOption('description', 'We value your privacy and we ensure that the information you give to us will not be shared to other entities.');

$form->addText('name', 'Your name:')
	->setRequired('Enter your name');

$form->addText('age', 'Your age:')
	->setRequired('Enter your age')
	->addRule($form::INTEGER, 'Age must be numeric value')
	->addRule($form::RANGE, 'Age must be in range from %d to %d', array(10, 100));

$form->addRadioList('gender', 'Your gender:', $sex);

$form->addText('email', 'Email:')
	->setEmptyValue('@')
	->addCondition($form::FILLED) // conditional rule: if is email filled, ...
		->addRule($form::EMAIL, 'Incorrect email address'); // ... then check email


// group Shipping address
$form->addGroup('Shipping address')
	->setOption('embedNext', TRUE);

$form->addCheckbox('send', 'Ship to address')
	->addCondition($form::EQUAL, TRUE) // conditional rule: if is checkbox checked...
		->toggle('sendBox'); // toggle div #sendBox


// subgroup
$form->addGroup()
	->setOption('container', Html::el('div')->id('sendBox'));

$form->addText('street', 'Street:');

$form->addText('city', 'City:')
	->addConditionOn($form['send'], $form::EQUAL, TRUE)
		->addRule($form::FILLED, 'Enter your shipping address');

$form->addSelect('country', 'Country:', $countries)
	->skipFirst()
	->addConditionOn($form['send'], $form::EQUAL, TRUE)
		->addRule($form::FILLED, 'Select your country');


// group Your account
$form->addGroup('Your account');

$form->addPassword('password', 'Choose password:')
	->setRequired('Choose your password')
	->addRule($form::MIN_LENGTH, 'The password is too short: it must be at least %d characters', 3);

$form->addPassword('password2', 'Reenter password:')
	->addConditionOn($form['password'], $form::VALID)
		->addRule($form::FILLED, 'Reenter your password')
		->addRule($form::EQUAL, 'Passwords do not match', $form['password']);

$form->addFile('avatar', 'Picture:')
	->addCondition($form::FILLED)
		->addRule($form::IMAGE, 'Uploaded file is not image');

$form->addHidden('userid');

$form->addTextArea('note', 'Comment:');


// group for buttons
$form->addGroup();

$form->addSubmit('submit', 'Send');



// Step 2: Check if form was submitted?
if ($form->isSubmitted()) {

	// Step 2c: Check if form is valid
	if ($form->isValid()) {
		echo '<h2>Form was submitted and successfully validated</h2>';

		Debugger::dump($form->values);

		// this is the end, my friend :-)
		exit;
	}

} else {
	// not submitted, define default values
	$defaults = array(
		'name'    => 'John Doe',
		'userid'  => 231,
		'country' => 'CZ', // Czech Republic
	);

	$form->setDefaults($defaults);
}



// Step 3: Render form
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">

	<title>Nette\Forms basic example | Nette Framework</title>

	<style type="text/css">
	.required {
		color: darkred
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
</head>

<body>
	<h1>Nette\Forms basic example</h1>

	<?php echo $form ?>
</body>
</html>
