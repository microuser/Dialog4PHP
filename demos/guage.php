<?php
use microuser\Dialog4Php\Guage;
require_once(__DIR__.'/../vendor/autoload.php');

$dp = new Guage();

$dp->setTitle("Her Yer Hourses")->setBackTitle("Backtitle");
$dp->setColorTheme(5);
$dp->setBody("Sattle Up!");



//If you want to change the title, place run within the loop. 
//This fills the screen buffer. really fast, because each screen is prints that many lines
for($i=0; $i<= 100; $i+=5){
    usleep(50000); //50ms
    if(($i % 20)){
        $dp->setTitle("Old School Blink");
    } else {
        $dp->setTitle("");
    }
    $dp->setBody("discombobulating the discombobulator\n");
    $dp->setPercent($i);
    $dp->run();

}


//If you want to change just the percent or the body, you can use Start, Update, [setBody], Stop combination. 
//This has the advantage of not filling the screen buffer.
$dp = new Guage();
$dp->setScreenHeight(12);
$dp->start();
for($i=100; $i>= 0; $i-=1){
    usleep(10000); 
    $dp->setBody("Erasing Time:\n");
    $dp->update($i);
}
$dp->stop();
