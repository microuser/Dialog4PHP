<?php

include_once(__DIR__.'/../Dialog4Php.php');

$dp = new Dialog4Php();
$dp->msgBox("This is a test of just body");
$dp->msgBox("This is a test of just body", "title");
$dp->msgBox("This is a test of just body", "title", "Backtitle");
