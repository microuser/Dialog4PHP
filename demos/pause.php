<?php

use microuser\Dialog4Php\Pause;

require_once(__DIR__.'/../vendor/autoload.php');

$dp = new Pause();
    
$dp->setBody("[]lease wait while adding character: P to everything.");
$dp->setScreenMax();
$dp->setPause(5);
if($dp->run()){
    echo "Pause completed";
} else {
    echo "Pause was canceled?";
}
