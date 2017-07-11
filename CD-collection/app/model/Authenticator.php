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


	public function __construct(Nette\Database\Context $database)
	{
		$this->database = $database;
	}


	/**
	 * Performs an authentication.
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials): Security\IIdentity
	{
		list($username, $password) = $credentials;
		$row = $this->database->table('users')->where('username', $username)->fetch();

		if (!$row) {
			throw new Security\AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);

		} elseif (!Security\Passwords::verify($password, $row->password)) {
			throw new Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);
		}

		$arr = $row->toArray();
		unset($arr['password']);
		return new Security\Identity($row->id, null, $arr);
	}
}
