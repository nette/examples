<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Model;
use Nette;
use Nette\Application\UI\Form;


class DashboardPresenter extends Nette\Application\UI\Presenter
{
	/** @var Model\AlbumRepository */
	private $albums;


	public function __construct(Model\AlbumRepository $albums)
	{
		$this->albums = $albums;
	}


	protected function startup(): void
	{
		parent::startup();

		if (!$this->getUser()->isLoggedIn()) {
			if ($this->getUser()->getLogoutReason() === Nette\Security\IUserStorage::INACTIVITY) {
				$this->flashMessage('You have been signed out due to inactivity. Please sign in again.');
			}
			$this->redirect('Sign:in', ['backlink' => $this->storeRequest()]);
		}
	}


	/********************* view default *********************/


	public function renderDefault(): void
	{
		$this->template->albums = $this->albums->findAll()->order('artist')->order('title');
	}


	/********************* views add & edit *********************/


	public function renderAdd(): void
	{
		$this['albumForm']['save']->caption = 'Add';
	}


	public function renderEdit(int $id): void
	{
		$form = $this['albumForm'];
		if (!$form->isSubmitted()) {
			$album = $this->albums->findById($id);
			if (!$album) {
				$this->error('Record not found');
			}
			$form->setDefaults($album);
		}
	}


	/********************* view delete *********************/


	public function renderDelete(int $id): void
	{
		$this->template->album = $this->albums->findById($id);
		if (!$this->template->album) {
			$this->error('Record not found');
		}
	}


	/********************* component factories *********************/


	/**
	 * Edit form factory.
	 */
	protected function createComponentAlbumForm(): Form
	{
		$form = new Form;
		$form->addText('artist', 'Artist:')
			->setRequired('Please enter an artist.');

		$form->addText('title', 'Title:')
			->setRequired('Please enter a title.');

		$form->addSubmit('save', 'Save')
			->setHtmlAttribute('class', 'default')
			->onClick[] = [$this, 'albumFormSucceeded'];

		$form->addSubmit('cancel', 'Cancel')
			->setValidationScope([])
			->onClick[] = [$this, 'formCancelled'];

		$form->addProtection();
		return $form;
	}


	public function albumFormSucceeded(Nette\Forms\Controls\SubmitButton $button): void
	{
		$values = $button->getForm()->getValues();
		$id = (int) $this->getParameter('id');
		if ($id) {
			$this->albums->findById($id)->update($values);
			$this->flashMessage('The album has been updated.');
		} else {
			$this->albums->insert($values);
			$this->flashMessage('The album has been added.');
		}
		$this->redirect('default');
	}


	/**
	 * Delete form factory.
	 */
	protected function createComponentDeleteForm(): Form
	{
		$form = new Form;
		$form->addSubmit('cancel', 'Cancel')
			->onClick[] = [$this, 'formCancelled'];

		$form->addSubmit('delete', 'Delete')
			->setHtmlAttribute('class', 'default')
			->onClick[] = [$this, 'deleteFormSucceeded'];

		$form->addProtection();
		return $form;
	}


	public function deleteFormSucceeded(): void
	{
		$this->albums->findById((int) $this->getParameter('id'))->delete();
		$this->flashMessage('Album has been deleted.');
		$this->redirect('default');
	}


	public function formCancelled(): void
	{
		$this->redirect('default');
	}
}
