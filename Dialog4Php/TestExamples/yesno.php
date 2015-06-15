<?php

include_once(__DIR__.'/../Dialog4Php.php');

$dp = new Dialog4Php();



if($dp->yesnoBox("Do you kick it with a tasty groove?","Main Title", "Back title",0)){
    $dp->infoBox("Funky");
}else {
    $dp->infoBox("Not cool bro?");
}
