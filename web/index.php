<?php
use \Symfony\Component\HttpFoundation as Http;
use \Silex\Application as Application;

require __DIR__ . '/../vendor/autoload.php';

$app = new Application();
$app->mount('/', new Xsolla\AppProvider());
$app->run();
