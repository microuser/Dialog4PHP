<?php

use microuser\Dialog4Php\TreeView;
use microuser\Dialog4Php\MsgBox;

require_once(__DIR__.'/../vendor/autoload.php');

$dp = new TreeView();

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
        array(
        array(2.1,2.1,'off'),
        array(2.2,2.2,'off'),
        array(
            array(2.21,2.21,'off'),
            array(2.22,2.22,'off')
        )
    ),
    array(3,3,'off'),
    array(4,4,'off'),
    array(5,5,'off'),
    array(6,6,'off'),
    array(7,7,'off'),
    array(8,8,'off'),
    array(9,9,'off'),
);

//$tree = array(array(1,"'1 '' ' '' '''' ",'off'));
$dp->setTree($tree);
$dp->setBody("Select one of the following");
$dp->setScreenMax();
if($dp->run()){
    //(new MsgBox())->setBody($dp->getLastResponse())->run();
}
else {
    echo "Dont' want nothing\n";
}