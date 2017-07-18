<?php

require('../vendor/autoload.php');
// header("Access-Control-Allow-Origin: *");
header("Content-type: application/xml");

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

$app->post('/getData', function() use($app) { 


  echo ("Welcome");
  
    $c = $_POST['url'];

    echo ($c);


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

  echo "Study " . $_GET['data'];
  echo 'Hello ' . htmlspecialchars($_POST["data"]) . '!';

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
     echo "Request";
    // collect value of input field
    $name = $_REQUEST['data'];
    if (empty($name)) {
        echo "Name is empty";
    } else {
        echo $name;
    }
}




    if ($_SERVER["REQUEST_METHOD"] == "POST") {
       echo "Post";
        // collect value of input field
        $name = $_POST['data']; 
        if (empty($name)) {
            echo "Name is empty";
        } else {
            echo $name;
        }
    }

  if (is_ajax()) {
    echo ("is_ajax()");
    if (isset($_POST["action"]) && !empty($_POST["action"])) { //Checks if action value exists
      $action = $_POST["action"];
      switch($action) { //Switch case for value of action
        case "test": test_function(); break;
      }
    }
  }

//Function to check if the request is an AJAX request
function is_ajax() {
  echo ("is_ajax() 2");
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

function test_function(){
  echo ("Test function");
  $return = $_POST;
  
  //Do what you need to do with the info. The following are some examples.
  //if ($return["favorite_beverage"] == ""){
  //  $return["favorite_beverage"] = "Coke";
  //}
  //$return["favorite_restaurant"] = "McDonald's";
  
  $return["json"] = json_encode($return);
  echo json_encode($return);
}
  
  // $url = 'http://pf.tradetracker.net/?aid=1&fid=251713&categoryType=2&additionalType=2&limit=10';
  // print XmlToJson::Parse($url);  
});

$app->run();
