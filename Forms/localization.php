<?php

/**
 * Nette\Forms localization example (requires Zend_Translate).
 */


require __DIR__ . '/vendor/autoload.php';

// set_include_path();
include_once 'Zend/Translate.php';

if (!class_exists('Zend_Translate')) {
	die('This example requires Zend Framework');
}

use Nette\Forms\Form,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html;

Debugger::enable();


class MyTranslator extends Zend_Translate implements Nette\Localization\ITranslator
{
	/**
	 * Translates the given string.
	 * @param  string   message
	 * @param  int      plural count
	 * @return string
	 */
	public function translate($message, $count = NULL)
	{
		return parent::translate($message);
	}
}


$translator = new MyTranslator('gettext', __DIR__ . '/messages.mo', 'cs');
$translator->setLocale('cs');


$form = new Form;
$form->setTranslator($translator);

// group Personal data
$form->addGroup('Personal data');
$form->addText('name', 'Your name:')
	->setRequired('Enter your name');

$form->addText('age', 'Your age:')
	->setRequired('Enter your age')
	->addRule($form::INTEGER, 'Age must be numeric value')
	->addRule($form::RANGE, 'Age must be in range from %d to %d', array(10, 100));

$form->addRadioList('gender', 'Your gender:', array(
	'm' => 'male',
	'f' => 'female',
));

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

$countries = array(
	'Europe' => array(
		'CZ' => 'Czech Republic',
		'SK' => 'Slovakia',
	),
	'US' => 'USA',
	'?'  => 'other',
);
$form->addSelect('country', 'Country:', $countries)
	->setPrompt('Select your country')
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

$form->addUpload('avatar', 'Picture:');

$form->addHidden('userid');

$form->addTextArea('note', 'Comment:');


// group for buttons
$form->addGroup();

$form->addSubmit('submit', 'Send');


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
<html>
<head>
	<meta charset="utf-8">
	<title>Nette\Forms localization example | Nette Framework</title>
	<link rel="stylesheet" media="screen" href="files/style.css" />
	<script src="http://nette.github.com/resources/js/netteForms.js"></script>
</head>

<body>
	<h1>Nette\Forms localization example</h1>

	<?php echo $form ?>
</body>
</html>
