<?php

use Dialog4Php\EditBox;
require_once(__DIR__ . '/../Dialog4Php.php');

$ib = new \Dialog4Php\EditBox();
$ib->setFilePath('editbox.php')
        ->setScreenMax()
        ->setTitle("someTitle")
        ->setBackTitle("backtitle")
        ->setColorTheme(0)
        ->run();

echo $ib->getResponse();