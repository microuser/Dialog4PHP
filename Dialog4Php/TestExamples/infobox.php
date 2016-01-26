<?php
use Dialog4Php\Dialog4Php;
use Dialog4Php\InfoBox;
include_once(__DIR__.'/../Dialog4Php.php');

$ib = new \Dialog4Php\InfoBox();
$ib->setBody('seris')
->setScreenMax()
->setTitle("someTitle")
//->setBackTitle("backtitle")
->setColorTheme(0)
->run();

