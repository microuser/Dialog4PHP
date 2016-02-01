<?php

namespace microuser\Dialog4Php;
use microuser\Dialog4Php\Dialog4Php;

class Pause extends Dialog4PHP {

    /**
     *
     * @var string
     */
    protected $type = "--pause";
    
    private $pause = 0;
    
    /**
     *
     * @param int $seconds
     * @return \Dialog4Php\EditBox
     */
    public function setPause($seconds) {
        $this->pause = $seconds;
        return $this;
    }
    /**
     * 
     * @return int
     */
    public function getPause(){
        return $this->pause;
    }

    /**
     * 
     * @param string $body
     * @return string
     */
    public function setBody($body){
        return $this->setTypeArgs($body);
    }
    
    /**
     * 
     * @return string
     */
    public function getBody(){
        return $this->getTypeArgs();
    }
    
    public function generatePause(){
        return ' ' . $this->getPause();
    }
    
    
    /**
     *
     * @return boolean
     */
    public function run() {
        $cmd = $this->generateProgram();
        $cmd .= $this->generateTitle();
        $cmd .= $this->generateBackTitle();
        $cmd .= $this->generateColorTheme();
        $cmd .= $this->generateType();
        $cmd .= $this->generateScreen();
        $cmd .= $this->generatePause();
        return $this->runCmd($cmd);
        
    }

}
