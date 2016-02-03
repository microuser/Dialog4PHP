<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace microuser\Dialog4Php;

use microuser\Dialog4Php\MsgBox;

/**
 * Description of MsgBox
 *
 * @author user
 */
class ProgramBox extends Dialog4Php {

    /**
     *
     * @var string 
     */
    protected $type = '--programbox';

    /**
     *
     * @var string 
     */
    private $commands = '';
    /**
     * 
     * @param string $body
     * @return microuser\Dialog4Php\MsgBox
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

    /**
     * 
     * @param string $commands
     */
    public function setCommand($commands){
        $this->commands = $commands;
    }
    
    /**
     * 
     * @return string
     */
    public function getCommand(){
        return $this->commands;
    }
    
    private function generatePipes(){
        return '( '.$this->getCommand().' ) | ';
    }
    /**
     * 
     * @return bool 
     */
    public function run() {
        $cmd = $this->generatePipes();
        $cmd .= $this->generateProgram();
        $cmd .= $this->generateTitle();
        $cmd .= $this->generateBackTitle();
        $cmd .= $this->generateColorTheme();
        $cmd .= $this->generateType();
        $cmd .= $this->generateScreen(0, 0);
        
        return $this->runCmd($cmd);
    }

}
