<?php

use microuser\Dialog4Php\EditBox;

require_once(__DIR__ . '/../vendor/autoload.php');

$ib = new EditBox();
$ib->setFilePath('editbox.php')
        ->setScreenMax()
        ->setTitle("someTitle")
        ->setBackTitle("backtitle")
        ->setColorTheme(0)
        ->run();

echo $ib->getResponse();