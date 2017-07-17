<?php

require('../vendor/autoload.php');

$app = new Silex\Application();
$app['debug'] = true;

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
  'monolog.logfile' => 'php://stderr',
));

// Register view rendering
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

// Our web handlers

$app->get('/', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('index.twig');
});

$app->get('/cowsay', function() use($app) {
  $app['monolog']->addDebug('cowsay');
  return "<pre>".\Cowsayphp\Cow::say("Cool beans")."</pre>";
});

$app->get('/getData', function() use($app) { 
  // $app['monolog']->addDebug('cowsay');
  // return "<pre>".\Cowsayphp\Cow::say("Cool beans")."</pre>";
  $xml_string = 'http://pf.tradetracker.net/?aid=1&fid=251713&categoryType=2&additionalType=2&limit=10';
  $xml = simplexml_load_string($xml_string);
  $json = json_encode($xml);
  $array = json_decode($json,TRUE);
  print $array;
});

$app->run();
