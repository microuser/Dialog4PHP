<?php
use Dialog4Php\Dialog4Php;
include_once(__DIR__.'/../Dialog4Php.php');

(new \Dialog4Php\D4P_InfoBox())
->setBody("hello")
->setTitle("someTitle")
->setBackTitle("backtitle")
->setColorTheme(1)
->run();


//$dp->infoBox("This is a test of just body");
//$dp->infoBox("Body", "Title");
//$dp->infoBox("Body","Title","Backtitle");