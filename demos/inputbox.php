<?php
include_once(__DIR__.'/../Dialog4Php.php');
$dp = new Dialog4Php();

$dp->setXY(80,11);
$dp->inputBox('Body','Text');
$dp->inputBox("Your last response was", $dp->getResponse());
