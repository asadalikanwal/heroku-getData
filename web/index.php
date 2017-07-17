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
  
  // $xml = simplexml_load_string($xml_string);
  // $json = json_encode($xml);
  // $array = json_decode($json,TRUE);
  // return $array;


    class XmlToJson {
      public function Parse ($url) {
        $fileContents= file_get_contents($url);
        $fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
        $fileContents = str_replace(array("\/"), '/', $fileContents);
        $fileContents = trim(str_replace('"', "'", $fileContents));
        $simpleXml = simplexml_load_string($fileContents);
        $json = json_encode($simpleXml);
        return $json;
      }

    }

   class xml2js {
      public function Parse ($xmlnode) {
          $root = (func_num_args() > 1 ? false : true);
          $jsnode = array();

          if (!$root) {
              if (count($xmlnode->attributes()) > 0){
                  $jsnode["$"] = array();
                  foreach($xmlnode->attributes() as $key => $value)
                      $jsnode["$"][$key] = (string)$value;
              }

              $textcontent = trim((string)$xmlnode);
              if (count($textcontent) > 0)
                  $jsnode["_"] = $textcontent;

              foreach ($xmlnode->children() as $childxmlnode) {
                  $childname = $childxmlnode->getName();
                  if (!array_key_exists($childname, $jsnode))
                      $jsnode[$childname] = array();
                  array_push($jsnode[$childname], xml2js($childxmlnode, true));
              }
              return $jsnode;
          } else {
              $nodename = $xmlnode->getName();
              $jsnode[$nodename] = array();
              array_push($jsnode[$nodename], xml2js($xmlnode, true));
              return json_encode($jsnode);
          }
      }   
   }

  $url = 'http://pf.tradetracker.net/?aid=1&fid=251713&categoryType=2&additionalType=2&limit=10';
  // print XmlToJson::Parse($url);  
   print xml2js::Parse($url);  
});

$app->run();
