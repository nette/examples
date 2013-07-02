<?php

/**
 * Nette\Forms manual form rendering.
 */


require __DIR__ . '/../../Nette/loader.php';

use Nette\Forms\Form,
	Nette\Diagnostics\Debugger;

Debugger::enable();


$form = new Form;
$form->addText('name');
$form->addText('age');
$form->addRadioList('gender', NULL, array(
	'm' => 'male',
	'f' => 'female',
));
$form->addText('email')->setEmptyValue('@');

$form->addCheckbox('send');
$form->addText('street');
$form->addText('city');
$form->addSelect('country', NULL, array(
	'Europe' => array(
		'CZ' => 'Czech Republic',
		'SK' => 'Slovakia',
	),
	'US' => 'USA',
	'?'  => 'other',
))->setPrompt('Select your country');

$form->addPassword('password');
$form->addPassword('password2');
$form->addUpload('avatar');
$form->addHidden('userid');
$form->addTextArea('note');

$form->addSubmit('submit');


// Define validation rules
$form['name']->setRequired('Enter your name');

$form['age']->setRequired('Enter your age');
$form['age']->addRule($form::INTEGER, 'Age must be numeric value');
$form['age']->addRule($form::RANGE, 'Age must be in range from %d to %d', array(10, 100));

// conditional rule: if is email filled, ...
$form['email']->addCondition($form::FILLED)
	->addRule($form::EMAIL, 'Incorrect email address'); // ... then check email

// another conditional rule: if is checkbox checked...
$form['send']->addCondition($form::EQUAL, TRUE)
	// toggle div #sendBox
	->toggle('sendBox');

$form['city']->addConditionOn($form['send'], $form::EQUAL, TRUE)
	->addRule($form::FILLED, 'Enter your shipping address');

$form['country']->addConditionOn($form['send'], $form::EQUAL, TRUE)
	->addRule($form::FILLED, 'Select your country');

$form['password']->setRequired('Choose your password');
$form['password']->addRule($form::MIN_LENGTH, 'The password is too short: it must be at least %d characters', 3);

$form['password2']->addConditionOn($form['password'], $form::VALID)
	->addRule($form::FILLED, 'Reenter your password')
	->addRule($form::EQUAL, 'Passwords do not match', $form['password']);


if ($form->isSubmitted()) {
	if ($form->isValid()) {
		echo '<h2>Form was submitted and successfully validated</h2>';
		Debugger::dump($form->values);

		exit; // here is usually redirect to another page
	}

} else {
	$form->setDefaults(array( // not submitted, define default values
		'name'    => 'John Doe',
		'userid'  => 231,
		'country' => 'CZ',
	));
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Nette\Forms manual form rendering | Nette Framework</title>
	<link rel="stylesheet" media="screen" href="files/style.css" />
	<script src="http://nette.github.com/resources/js/netteForms.js"></script>
</head>

<body>
	<h1>Nette\Forms manual form rendering</h1>

	<?php $form->render('begin') ?>

	<?php if ($form->errors): ?>
	<p>Opravte chyby:</p>
	<?php $form->render('errors') ?>
	<?php endif ?>

	<fieldset>
		<legend>Personal data</legend>
		<table>
		<tr class="required">
			<th><?php echo $form['name']->getLabel('Your name:') ?></th>
			<td><?php echo $form['name']->control->cols(35) ?></td>
		</tr>
		<tr class="required">
			<th><?php echo $form['age']->getLabel('Your age:') ?></th>
			<td><?php echo $form['age']->control->cols(5) ?></td>
		</tr>
		<tr>
			<th><?php echo $form['gender']->getLabel('Your gender:') ?></th>
			<td><?php echo $form['gender']->control ?></td>
		</tr>
		<tr>
			<th><?php echo $form['email']->getLabel('Email:') ?></th>
			<td><?php echo $form['email']->control->cols(35) ?></td>
		</tr>
		</table>
	</fieldset>


	<fieldset>
		<legend>Shipping address</legend>

		<p><?php echo $form['send']->control?><?php echo $form['send']->getLabel('Ship to address') ?></p>

		<table id="sendBox">
		<tr>
			<th><?php echo $form['street']->getLabel('Street:') ?></th>
			<td><?php echo $form['street']->control->cols(35) ?></td>
		</tr>
		<tr class="required">
			<th><?php echo $form['city']->getLabel('City:') ?></th>
			<td><?php echo $form['city']->control->cols(35) ?></td>
		</tr>
		<tr class="required">
			<th><?php echo $form['country']->getLabel('Country:') ?></th>
			<td><?php echo $form['country']->control ?></td>
		</tr>
		</table>
	</fieldset>


	<fieldset>
		<legend>Your account</legend>
		<table>
		<tr class="required">
			<th><?php echo $form['password']->getLabel('Choose password:') ?></th>
			<td><?php echo $form['password']->control->cols(20) ?></td>
		</tr>
		<tr class="required">
			<th><?php echo $form['password2']->getLabel('Reenter password:') ?></th>
			<td><?php echo $form['password2']->control->cols(20) ?></td>
		</tr>
		<tr>
			<th><?php echo $form['avatar']->getLabel('Picture:') ?></th>
			<td><?php echo $form['avatar']->control ?></td>
		</tr>
		<tr>
			<th><?php echo $form['note']->getLabel('Comment:') ?></th>
			<td><?php echo $form['note']->control->cols(30)->rows(5) ?></td>
		</tr>
		</table>
	</fieldset>

	<div>
		<?php echo $form['userid']->control ?>
		<?php echo $form['submit']->getControl('Send') ?>
	</div>

	<?php $form->render('end'); ?>
</body>
</html>
