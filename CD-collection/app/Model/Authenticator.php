<?php

declare(strict_types=1);

namespace App\Model;

use Nette;
use Nette\Security;


/**
 * Users authenticator.
 */
class Authenticator implements Security\IAuthenticator
{
	use Nette\SmartObject;

	/** @var Nette\Database\Context */
	private $database;

	/** @var Security\Passwords */
	private $passwords;


	public function __construct(Nette\Database\Context $database, Security\Passwords $passwords)
	{
		$this->database = $database;
		$this->passwords = $passwords;
	}


	/**
	 * Performs an authentication.
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials): Security\IIdentity
	{
		[$username, $password] = $credentials;
		$row = $this->database->table('users')->where('username', $username)->fetch();

		if (!$row) {
			throw new Security\AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);

		} elseif (!$this->passwords->verify($password, $row->password)) {
			throw new Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);
		}

		$arr = $row->toArray();
		unset($arr['password']);
		return new Security\Identity($row->id, null, $arr);
	}
}
