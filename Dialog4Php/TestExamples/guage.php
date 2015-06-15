<?php

include_once(__DIR__.'/../Dialog4Php.php');

$dp = new Dialog4Php();


$dp->guageStart("Hold Yer Horses.");
for($i=0; $i<= 100; ++$i){
    usleep(10000); //50ms
    $dp->guageUpdate($i);
}
$dp->guageStop();


$dp->setXY(80, 10);
$dp->guageStart("Erasing History", "Universe Wipe", "The Borg Collective");
for($i=100; $i>=0; --$i){
    usleep(1000); 
    $dp->guageUpdate($i);
}
$dp->guageStop();
$dp->resetXY();