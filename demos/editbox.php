<?php

use microuser\Dialog4Php\EditBox;

require_once(__DIR__ . '/../vendor/autoload.php');

$ib = new EditBox();
$ib->setFilePath(__DIR__ . '/loremipsum.txt')
        ->setScreenMax()
        ->setTitle("someTitle")
        ->setBackTitle("backtitle")
        ->setColorTheme(0);

if ($ib->run()) {
    //When the user presses OK, the edited file is placed in getResponse().
    //The original file is not edited.
    echo "\n".$ib->getLastResponse()."\n";
}


