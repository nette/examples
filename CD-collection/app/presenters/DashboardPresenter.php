<?php

use Nette\Application\UI\Form,
	Nette\Application as NA;



class DashboardPresenter extends BasePresenter
{
	/** @var Nette\Database\Table\Selection */
	private $albums;



	protected function startup()
	{
		parent::startup();

		$this->albums = $this->getService('model')->getAlbums();

		// user authentication
		if (!$this->user->isLoggedIn()) {
			if ($this->user->logoutReason === Nette\Http\User::INACTIVITY) {
				$this->flashMessage('You have been signed out due to inactivity. Please sign in again.');
			}
			$backlink = $this->application->storeRequest();
			$this->redirect('Sign:in', array('backlink' => $backlink));
		}
	}



	/********************* view default *********************/



	public function renderDefault()
	{
		$this->template->albums = $this->albums->order('artist')->order('title');
	}



	/********************* views add & edit *********************/



	public function renderAdd()
	{
		$this['albumForm']['save']->caption = 'Add';
	}



	public function renderEdit($id = 0)
	{
		$form = $this['albumForm'];
		if (!$form->isSubmitted()) {
			$row = $this->albums->get($id);
			if (!$row) {
				throw new NA\BadRequestException('Record not found');
			}
			$form->setDefaults($row);
		}
	}



	/********************* view delete *********************/



	public function renderDelete($id = 0)
	{
		$this->template->album = $this->albums->get($id);
		if (!$this->template->album) {
			throw new NA\BadRequestException('Record not found');
		}
	}



	/********************* component factories *********************/



	/**
	 * Album edit form component factory.
	 * @return mixed
	 */
	protected function createComponentAlbumForm()
	{
		$form = new Form;
		$form->addText('artist', 'Artist:')
			->setRequired('Please enter an artist.');

		$form->addText('title', 'Title:')
			->setRequired('Please enter a title.');

		$form->addSubmit('save', 'Save')->setAttribute('class', 'default');
		$form->addSubmit('cancel', 'Cancel')->setValidationScope(NULL);
		$form->onSubmit[] = callback($this, 'albumFormSubmitted');

		$form->addProtection('Please submit this form again (security token has expired).');
		return $form;
	}



	public function albumFormSubmitted(Form $form)
	{
		if ($form['save']->isSubmittedBy()) {
			$id = (int) $this->getParam('id');
			if ($id > 0) {
				$this->albums->find($id)->update($form->values);
				$this->flashMessage('The album has been updated.');
			} else {
				$this->albums->insert($form->values);
				$this->flashMessage('The album has been added.');
			}
		}

		$this->redirect('default');
	}



	/**
	 * Album delete form component factory.
	 * @return mixed
	 */
	protected function createComponentDeleteForm()
	{
		$form = new Form;
		$form->addSubmit('cancel', 'Cancel');
		$form->addSubmit('delete', 'Delete')->setAttribute('class', 'default');
		$form->onSubmit[] = callback($this, 'deleteFormSubmitted');
		$form->addProtection('Please submit this form again (security token has expired).');
		return $form;
	}



	public function deleteFormSubmitted(Form $form)
	{
		if ($form['delete']->isSubmittedBy()) {
			$this->albums->find($this->getParam('id'))->delete();
			$this->flashMessage('Album has been deleted.');
		}

		$this->redirect('default');
	}

}
