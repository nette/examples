<?php

// Nette Framework TweetReader example

use Nette\Diagnostics\Debugger,
	Nette\Application\Routers\Route,
	Nette\Utils\Json;


// load framework
require __DIR__ . '/../../../Nette/loader.php';


// enable Debugger
Debugger::$logDirectory = __DIR__ . '/data/log';
Debugger::$strictMode = TRUE;
Debugger::enable();


// create application
$configurator = new Nette\Configurator;
$context = $configurator->container;
$application = $context->application;
$context->params['tempDir'] = __DIR__ . '/data/temp';


$application->router[] = new Route('[index.php]', function() {
	return 'Hello! Would you like to search <a href="search/nettefw">#nettefw</a> on Twitter?';
});

$application->router[] = new Route('search/<hashtag \w+>', function($hashtag, $presenter) {
	$params['response'] = Json::decode(file_get_contents('http://search.twitter.com/search.json?q=' . urlencode("#$hashtag")));
	return array('
		<h1>Results for #{$hashtag}</h1>

		{foreach $response->results as $item}
			<p><img src="{$item->profile_image_url}" width="48"><em>{$item->from_user}</em>: {$item->text}
				<small>at {$item->created_at|date:"j.n.Y H:i"}</small></p>
		{/foreach}
	', $params);
});


// run the application!
$application->run();
