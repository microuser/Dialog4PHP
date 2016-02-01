<?php

use microuser\Dialog4Php\TimeBox;

require_once(__DIR__.'/../vendor/autoload.php');

$dp = new TimeBox();
$dp->setBackTitle("Set Military Time");
$dp->setTitle("What day do potatoes hate the most? Fryday");    
$dp->setBody("What does a clock do when its hungry? It goes back for seconds");
$dp->setHour(23);
$dp->setMinute(0);
$dp->setSecond(55);

$dp->setTime("23:00:40");
$dp->setTime("10:00:00 PM");
$dp->setTime(array('09','09','09'));
$dp->setTime(array('hour' => '08', 'minute' => '08','second' => '08'));
//$dp->setScreenMax();
if($dp->run()){
    echo $dp->getLastResponse();
} else {
    echo "Pause was canceled?";
}
