<?php
use microuser\Dialog4Php\InputBox;
use microuser\Dialog4Php\Dialog4Php;

require_once(__DIR__.'/../vendor/autoload.php');

$dp = new InputBox();
$dp->setBody("Body Message");
$dp->setInput("Default Text");
$dp->setTitle("Title Text");

if($dp->run()){
    echo PHP_EOL."User Response: ".$dp->getLastResponse(). PHP_EOL;
} else {
    echo PHP_EOL."User Pressed Cancel or Escape Key". PHP_EOL;
}
