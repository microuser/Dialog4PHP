<?php

include_once(__DIR__.'/../Dialog4Php.php');

$dp = new Dialog4Php();

$tree = array(
    array(1,1,'on'),
    array(
        array(1.1,1.1,'off'),
        array(1.2,1.2,'off'),
        array(
            array(1.21,1.21,'off'),
            array(1.22,1.22,'off')
        )
    ),
    array(2,2,'off')
);
if($dp->treeView("Select one of the following", 8, $tree)){
    $dp->infoBox($dp->setXY(80,10)->getResponse());
}
else {
    echo "Dont' want nothing\n";
}