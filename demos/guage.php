<?php
use microuser\Dialog4Php\Guage;

require_once(__DIR__.'/../Dialog4Php.php');

$dp = new Dialog4Php\Guage();


$dp->setTitle("Her Yer Hourses")->setBackTitle("Backtitle");
$dp->setColorTheme(5);
$dp->setBody("some body");


//$dp->start();
for($i=0; $i<= 100; $i+=10){
    usleep(500000); //50ms
    //$dp->update($i);
    $dp->setBody("so close".$i);
    $dp->setPercent($i);
    $dp->run();
}
//$dp->stop();
