<?php

use microuser\Dialog4Php\ProgramBox;

require_once(__DIR__ . '/../vendor/autoload.php');

$ib = new ProgramBox();
$ib->setBody("Body")//->setDryRun(true)
        //->setScreenMax()
        ->setTitle("someTitle")
        ->setBackTitle("backtitle")
        ->setColorTheme(0)
        ->setCommand('ifconfig | grep inet');

if ($ib->run()) {
    //When the user presses OK, the edited file is placed in getResponse().
    //The original file is not edited.
    echo "\n".$ib->getLastResponse()."\n";
}


