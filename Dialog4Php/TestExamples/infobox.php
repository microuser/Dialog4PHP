<?php

include_once(__DIR__.'/../Dialog4Php.php');

$dp = new Dialog4Php();
$dp->infoBox("This is a test of just body");
$dp->infoBox("Body", "Title");
$dp->infoBox("Body","Title","Backtitle");