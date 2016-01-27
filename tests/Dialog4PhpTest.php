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
    public function setProgram($program){
        return parent::setProgram($program);
    }
    public function getScreenHeight(){
        return parent::getScreenHeight();
    }
    public function getScreenWidth(){
        return parent::getScreenWidth();
    }
    public function getTitle(){
        return parent::getTitle();
    }
    public function getType(){
        return parent::getType();
    }
    public function getLastCommand(){
        return parent::getLastCommand();
    }
    public function setDryRun($dryRun){
        parent::setDryRun($dryRun);
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
        $this->assertTrue(is_object($this->d->setProgram("Progra")));
        $this->assertTrue(is_object($this->d->setScreen80x24()));
        $this->assertTrue(is_object($this->d->setScreenMax()));
        $this->assertTrue(is_object($this->d->setScreenHeight(80)));
        $this->assertTrue(is_object($this->d->setScreenWidth(80)));
        $this->assertTrue(is_object($this->d->setTitle("Backtitle")));
        $this->assertTrue(is_object($this->d->setType("Type")));
        $this->assertTrue(is_object($this->d->setTypeArgs("TypeArgs With Special Characters quote ' hash # minus - doublequote \"")));
    }
    
    public function testSetGetBackTitle(){
        
        //Mind Blown master of Bash and PHP Escaping
        //This yields in the terminal: `$#^#'"'"'\ \'"'"' "|\\<LINE RETURN>
        $this->d->setBackTitle("`$#^#'\\ \\' \"|\\\\\n");
        $this->assertEquals("`$#^#'\"'\"'\\ \\'\"'\"' \"|\\\\\n" , $this->d->getBackTitle());
        
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
    
    public function testSetGetProgram(){
        $this->d->setProgram("Program");
        $this->assertEquals("Program",$this->d->getProgram());
    }
    
    public function testSetGetResponse(){
        //In runCmd, the response pipe is 3. so we need to redirect STDOUT to &3 for this to work
        $this->d->runCmd('echo \'Response\' >&3;');
        $this->assertTrue(is_string($this->d->getResponse()));
        $this->assertEquals("Response\n", $this->d->getResponse());
    }
    
    public function testSetGetScreenHeight(){
        $this->d->setScreenHeight(42);
        $this->assertEquals(42, $this->d->getScreenHeight());
    }
    public function testSetGetScreenWidth(){
        $this->d->setScreenWidth(42);
        $this->assertEquals(42, $this->d->getScreenWidth());
    }
    
    public function testSetGetTitle(){
        $this->d->setTitle("Title");
        $this->assertEquals("Title",$this->d->getTitle());
    }
    
    public function testSetGetType(){
        $this->d->setType("Type");
        $this->assertEquals("Type",$this->d->getType());
    }
    
    public function testSetScreenMax(){
        //This might require that PHPUnit is ran from within a terminal.
        //I attempted to run this with: nohup vendor/bin/phpunit and it seems to emulate a 24x80 screen
        //But your milage may varry
        $this->d->setScreenMax();
        $this->assertTrue(is_string($this->d->getResponse()));
        $this->assertTrue(strlen($this->d->getResponse()) > 0);
        $this->assertTrue(is_numeric($this->d->getScreenHeight()));
        $this->assertTrue(is_numeric($this->d->getScreenWidth()));
    }
    
    public function testSetGetDryRun(){
        $this->d->setDryRun(true);
        $this->d->setScreenHeight(-1);
        $this->d->setScreenMax();
        //This Proves that DryRun does not allow execuition. Because SetScreenMax() did not run.
        $this->assertEquals(-1,$this->d->getScreenHeight());
        //This shows that the command was ready to run.
        $this->assertEquals('dialog --output-fd 3 --print-maxsize',$this->d->getLastCommand());
        //I wonder if there is a way to capture the output of the php echo to see what it printed during running this.
        
    }
    
    public function testGenerators(){
        
    }
}
