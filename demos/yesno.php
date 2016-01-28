<?php

use microuser\Dialog4Php\YesNo;
use microuser\Dialog4Php\MsgBox;

require_once(__DIR__ . '/../vendor/autoload.php');

//If you really want to be cheeky
if((new YesNo)->setBody("Chaining without a variable")->setTitle("So Cheeky")->run()){
    (new MsgBox)->setBody("This syntax is a bit ugly but gets stright to the point")->run();
}

//For a bit of normal code
$ib = new YesNo();
$ib->setBody("asdf");
$ib->setScreenMax();
$ib->setTitle("someTitle");
$ib->setBackTitle("backtitle");
$ib->setColorTheme(0);
if ($ib->run()) {
    echo "The Run function returns a boolean which indicates that the user has pressed Yes" . PHP_EOL;
} else {
    echo "The Run function returns a boolean which indicates that the user has pressed No or Cancel" . PHP_EOL;
}



//You don't need to capture run when you use it, you can get its return status later
if ($ib->getLastReturnStatus()) {
    echo "The getLastReturnStatus returns a boolean of the return status which was set at the last time run() was called. It indicates the user has pressed Yes." . PHP_EOL;
} else {
    echo "The getLastReturnStatus returns a boolean of the return status which was set at the last time run() was called. It indicates the user has pressed No or Cancel." . PHP_EOL;
}

//You can actually differentiate between No and Escape. 
//This is cool because its backward compable with false and null being type castable in an if() condition or if you choose to use just double ==, rather than tripple ===
switch ($ib->getLastReturnStatus()){
    case true : 
        echo "User Pressed Yes".PHP_EOL; 
        break;
    case false : 
        echo "User Pressed No".PHP_EOL; 
        break;
    case null : 
        echo "User Pressed Escape on their keyboard".PHP_EOL; 
        break;
}


if ($ib->getResponse()) {
    "The Yes No box never has a response. You should never see this message. For the response is for things with user input.";
}

