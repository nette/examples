<?php

// let bootstrap create Dependency Injection container
$container = require __DIR__ . '/../app/bootstrap.php';

// run application
$container->getService('application')->run();
