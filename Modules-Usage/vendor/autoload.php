<?php

// This is default autoload.php. It can be overwritten by Composer.

if (!is_file(__DIR__ . '/../../../Nette/loader.php')) {
	die("Nette Framework is expected in directory '" . __DIR__ . "/Nette' but not found. Edit file '" . __FILE__ . "' or execute `composer update`.");
}

require __DIR__ . '/../../../Nette/loader.php';
