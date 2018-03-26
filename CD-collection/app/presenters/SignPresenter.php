<?php
declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI;


class SignPresenter extends Nette\Application\UI\Presenter
{
	/** @persistent */
	public $backlink = '';


	/**
	 * Sign-in form factory.
	 */
	protected function createComponentSignInForm(): UI\Form
	{
		$form = new UI\Form;
		$form->addText('username', 'Username:')
			->setRequired('Please enter your username.');

		$form->addPassword('password', 'Password:')
			->setRequired('Please enter your password.');

		$form->addSubmit('send', 'Sign in');

		$form->onSuccess[] = [$this, 'signInFormSucceeded'];
		return $form;
	}


	public function signInFormSucceeded(UI\Form $form, \stdClass $values): void
	{
		try {
			$this->getUser()->login($values->username, $values->password);

		} catch (Nette\Security\AuthenticationException $e) {
			$form->addError($e->getMessage());
			return;
		}

		$this->restoreRequest($this->backlink);
		$this->redirect('Dashboard:');
	}


	public function actionOut(): void
	{
		$this->getUser()->logout();
		$this->flashMessage('You have been signed out.');
		$this->redirect('in');
	}
}
