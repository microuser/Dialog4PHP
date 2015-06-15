<?php
include_once(__DIR__.'/../Dialog4Php.php');
$dp = new Dialog4Php();

$dp->editBox('/home/user/NetBeansProjects/Dialog4PHP/README.md', "Edit your readme here", "Edit fun");
echo $dp->getResponse();