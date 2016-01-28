<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace microuser\Dialog4Php;
use microuser\Dialog4Php\Dialog4Php;
/**
 * Description of YesNo
 *
 * @author user
 */
class YesNo extends Dialog4Php {
    
    protected $type = '--yesno';
    
    /**
     * 
     * @param string $body
     * @return \microuser\Dialog4Php\YesNo
     */
    public function setBody($body){
        return $this->setTypeArgs($body);
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
        return $this->runCmd($cmd);
    }
    
}
