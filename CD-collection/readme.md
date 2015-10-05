CD collection (Nette Framework example)
---------------------------------------

Classic Zend Framework [Tutorial](http://akrabat.com/zend-framework-tutorial)
rewritten for Nette Framework.

The example shows an important feature of the Nette Framework: the URLs are
not used inside the application including the templates. The URLs are in
responsibility of the router and can be changed anytime. The target of a link
is always a combination "Presenter:action" or "Presenter:signal!".


What is [Nette Framework](https://nette.org)?
--------------------------------------------

Nette Framework is a popular tool for PHP web development. It is designed to be
the most usable and friendliest as possible. It focuses on security and
performance and is definitely one of the safest PHP frameworks.

Nette Framework speaks your language and helps you to easily build better websites.


Installing
----------

The best way to install Nette Framework is to download latest package
from https://nette.org/download or using [Composer](https://doc.nette.org/composer):

	curl -s http://getcomposer.org/installer | php
	php composer.phar update

Then navigate your browser to the `www` directory. PHP 5.4 allows
you run `php -S localhost:8888 -t www` to start the webserver and
then visit `http://localhost:8888` in your browser.
