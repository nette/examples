<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

// enable Tracy
// see https://doc.nette.org/en/bootstrap
$configurator = new Nette\Configurator;
$configurator->enableTracy(__DIR__ . '/log');

// create DI container
$configurator->setTempDirectory(__DIR__ . '/temp');
$configurator->addConfig(__DIR__ . '/config/sqlite.neon'); // for SQLite
//$configurator->addConfig(__DIR__ . '/config/mysql.neon'); // for MySQL
//$configurator->addConfig(__DIR__ . '/config/postgresql.neon'); // for PostgreSQL
//$configurator->addConfig(__DIR__ . '/config/sqlsrv.neon'); // for MS SQL Server
$container = $configurator->createContainer();

// get database from DI container
// see https://doc.nette.org/en/di-configuration
/** @var Nette\Database\Context $database */
$database = $container->getByType(Nette\Database\Context::class);

// load database dump
Nette\Database\Helpers::loadFromFile(
	$database->getConnection(),
	$container->parameters['dumpFile'] // defined in config file
);

// lists the author's name for each book and all its tags:
// see https://doc.nette.org/en/database-explorer
$books = $database->table('book');

echo PHP_SAPI === 'cli' ? '' : '<xmp>';

foreach ($books as $book) {
	echo "title:      {$book->title} \n";
	echo "written by: {$book->author->name} \n"; // $book->author is row from table 'author'

	echo 'tags: ';
	foreach ($book->related('book_tag') as $bookTag) {
		echo $bookTag->tag->name . ', '; // $bookTag->tag is row from table 'tag'
	}
	echo "\n\n";
}

echo PHP_SAPI === 'cli' ? '' : '</xmp>';
