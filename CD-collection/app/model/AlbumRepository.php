<?php
declare(strict_types=1);

namespace App\Model;

use Nette;


class AlbumRepository
{
	use Nette\SmartObject;

	/** @var Nette\Database\Context */
	private $database;


	public function __construct(Nette\Database\Context $database)
	{
		$this->database = $database;
	}


	public function findAll(): Nette\Database\Table\Selection
	{
		return $this->database->table('albums');
	}


	public function findById(int $id): Nette\Database\Table\ActiveRow
	{
		return $this->findAll()->get($id);
	}


	public function insert(iterable $values): void
	{
		$this->findAll()->insert($values);
	}
}
