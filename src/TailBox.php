<?php

namespace microuser\Dialog4Php;
use microuser\Dialog4Php\Dialog4Php;

class TailBox extends Dialog4PHP {

    /**
     *
     * @var string
     */
    protected $type = "--tailbox";

    
    /**
     * 
     * @param string $text
     * @return \microuser\Dialog4Php\TextBox
     */
    public function setExitLabel($text){
        $this->exitLabel = $text;
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getExitLabel(){
        return $this->exitLabel;
    }
    
    /**
     *
     * @param string $filePath
     * @return \Dialog4Php\EditBox
     */
    public function setFilePath($filePath) {
        return $this->setTypeArgs($filePath);
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
        $cmd .= $this->generateOptionals();
        $cmd .= $this->generateType();
        $cmd .= $this->generateScreen();
        return $this->runCmd($cmd);
        
    }

}
