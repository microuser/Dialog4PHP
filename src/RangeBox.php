<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace microuser\Dialog4Php;
/**
 * Description of MsgBox
 *
 * @author user
 */
class RangeBox extends Dialog4Php {

    protected $type = '--rangebox';

    /**
     *
     * @var int
     */
    private $min = 0;
    
    /**
     *
     * @var int
     */
    private $max = 100;
    
    /**
     *
     * @var int
     */
    private $default = 0;
    
    /**
     * 
     * @param type $min
     * @return \microuser\Dialog4Php\RangeBox
     */
    public function setMin($min){
        $this->min = $min;
        return $this;
    }
    
    /**
     * 
     * @param type $max
     * @return \microuser\Dialog4Php\RangeBox
     */
    public function setMax($max){
        $this->max = $max;
        return $this;
    }
    
    /**
     * 
     * @param type $default
     * @return \microuser\Dialog4Php\RangeBox
     */
    public function setDefault($default){
        $this->default = $default;
        return $this;
    }
    
    /**
     * 
     * @return type
     */
    public function getMin(){
        return $this->min;
    }
    
    /**
     * 
     * @return type
     */
    public function getMax(){
        return $this->max;
    }
    /**
     * 
     * @return type
     */
    public function getDefault(){
        return $this->default;
    }
    
    protected function generateValues(){
        return ' '.$this->getMin().' '.$this->getMax() .' '. $this->getDefault();
    }
    
    
    
    
    
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
     * @return bool 
     */
    public function run() {
        $cmd = $this->generateProgram();
        $cmd .= $this->generateTitle();
        $cmd .= $this->generateBackTitle();
        $cmd .= $this->generateColorTheme();
        $cmd .= $this->generateType();
        $cmd .= $this->generateScreen(4, 6);
        $cmd .= $this->generateValues();
        return $this->runCmd($cmd);
    }

}
