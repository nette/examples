<?php

use Nette\Object;


/**
 * Model base class.
 */
class Model extends Object
{
	/** @var Nette\Database\Connection */
	public $database;


	public function __construct(Nette\Database\Connection $database)
	{
		$this->database = $database;
	}



	public function getAlbums()
	{
		return $this->database->table('albums');
	}



	public function createAuthenticatorService()
	{
		return new Authenticator($this->database->table('users'));
	}

}
