<?php
use microuser\Dialog4Php\MsgBox;
use microuser\Dialog4Php\Dialog4Php;

require_once(__DIR__.'/../vendor/autoload.php');


$ib = new MsgBox();
$ib->setBody('seris')
->setScreenMax()
->setTitle("someTitle")
//->setBackTitle("backtitle")
->setColorTheme(0)
->run();

