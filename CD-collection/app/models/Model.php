<?php

use Nette\Object;


/**
 * Model base class.
 */
class Model extends Object
{
	/** @var Nette\Database\Connection */
	public static $database;


	public static function initialize($options)
	{
		self::$database = new Nette\Database\Connection($options->dsn, $options->user, $options->pass);
	}


	public static function albums()
	{
		return self::$database->table('albums');
	}

}
