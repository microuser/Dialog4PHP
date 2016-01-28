<?php

include_once(__DIR__.'/../Dialog4Php.php');

$dp = new Dialog4Php();
if($dp->setXY(80,14)->timeBox("What does a clock do when its hungry? It goes back for seconds", null, null, null, "Set Military Time" , "What day do potatoes hate the most? Fryday")){
    echo $dp->getLastResponse();
}