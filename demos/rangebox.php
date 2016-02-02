<?php
use microuser\Dialog4Php\RangeBox;

require_once(__DIR__.'/../vendor/autoload.php');


$ib = new RangeBox();
$ib->setBody('seris')
        ->setBackTitle("Backtitle")
        ->setScreenMax()
        ->setScreenHeight(max(array($ib->getScreenHeight()/2,10)))
        ->setTitle("someTitle")
        ->setColorTheme(0)
        ->setMax(200)
        ->setMin(100)
        ->setDefault(150);
if($ib->run()){
    echo $ib->getLastResponse(). PHP_EOL;
}

