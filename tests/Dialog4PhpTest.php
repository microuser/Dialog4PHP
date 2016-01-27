<?php

use microuser\Dialog4Php\Dialog4Php;

class Dialog4Php_Testable extends Dialog4Php{
    public static function escapeSingleQuote($unescaped){
        return parent::escapeSingleQuote($unescaped);
    }
    public function setType($type){
        return parent::setType($type); 
    }
    public function setTypeArgs($typeArgs){
        return parent::setTypeArgs($typeArgs);
    }
    public function getBackTitle() {
        return parent::getBackTitle();
    }
    public function getColorTheme($item){
        return parent::getColorTheme($item);
    }
    public function getExitCode(){
        return parent::getExitCode();
    }
    public function getProgram(){
        return parent::getProgram();
    }
    public function runCmd($cmd) {
        return parent::runCmd($cmd);
    }
    
    
}

class Dialog4PhpTest extends \PHPUnit_Framework_TestCase {
    
    private $d ;
    public function setUp() {
        parent::setUp();
        $this->d = new Dialog4Php_Testable();
    }
    
    public function testEscapeSingleQuote(){
        $this->assertEquals("I'\"'\"'m a student", Dialog4Php_Testable::escapeSingleQuote("I'm a student"));
    }
    
    public function testChainableFunctions(){
        $this->assertTrue(is_object($this->d->setBackTitle("BackTitle")));
        $this->assertTrue(is_object($this->d->setColorTheme(5)));
        $this->assertTrue(is_object($this->d->setEscapeKeyReturn(false)));
        $this->assertTrue(is_object($this->d->setExitOnError(false)));
        $this->assertTrue(is_object($this->d->setScreen80x24()));
        $this->assertTrue(is_object($this->d->setScreenMax()));
        $this->assertTrue(is_object($this->d->setScreenHeight(80)));
        $this->assertTrue(is_object($this->d->setScreenWidth(80)));
        $this->assertTrue(is_object($this->d->setTitle("Backtitle")));
        $this->assertTrue(is_object($this->d->setType("Type")));
        $this->assertTrue(is_object($this->d->setTypeArgs("TypeArgs With Special Characters quote ' hash # minus - doublequote \"")));
    }
    
    public function testSetGetBackTitle(){
        $this->d->setBackTitle("BackTitle");
        $this->assertEquals("BackTitle", $this->d->getBackTitle());
    }
    
    public function testSetGetColorThemeCritical(){
        $this->d->setColorTheme(Dialog4Php::D4P_THEME_CRITICAL);
        $this->assertEquals('\Z1',$this->d->getColorTheme('backtitle'));
        $this->assertEquals('\Z7',$this->d->getColorTheme('title'));
        $this->assertEquals('\Z1',$this->d->getColorTheme('body'));
        $this->assertEquals(' --colors',$this->d->getColorTheme('enable'));
    }
    
    public function testSetGetExitCode(){
        $this->d->runCmd("true");
        $this->assertEquals("0",$this->d->getExitCode());
        $this->d->runCmd("false");
        $this->assertEquals("1",$this->d->getExitCode());
    }
    
    public function testGenerators(){
        
    }
}
