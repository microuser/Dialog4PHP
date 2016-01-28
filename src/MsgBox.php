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
class MsgBox extends Dialog4Php {

    protected $type = '--msgbox';

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
        $cmd .= $this->generateScreen(0, 0);
        return $this->runCmd($cmd);
    }

}
