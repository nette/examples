<?php



class AlbumRepository extends Nette\Object
{
	/** @var Nette\Database\SelectionFactory */
	private $database;



	public function __construct(Nette\Database\SelectionFactory $database)
	{
		$this->database = $database;
	}



	/** @return Nette\Database\Table\Selection */
	public function findAll()
	{
		return $this->database->table('albums');
	}



	/** @return Nette\Database\Table\ActiveRow */
	public function findById($id)
	{
		return $this->findAll()->get($id);
	}



	public function insert($values)
	{
		return $this->findAll()->insert($values);
	}

}
