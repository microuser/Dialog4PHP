<?php
namespace Dialog4Php;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of D4P_InfoBox
 *
 * @author user
 */
class InfoBox extends Dialog4PHP{// implements Dialog4PhpAbstract {

    protected $type = "--infobox";
    
    public function setBody($body){
        parent::setTypeArgs($body);
        return $this;
    }

    public function run() {
        $cmd = $this->generateProgram();
        $cmd .= $this->generateTitle();
        $cmd .= $this->generateBackTitle();
        $cmd .= $this->generateColorTheme();
        $cmd .= $this->generateType();
        $cmd .= $this->generateScreen(0, 0);
        if ($this->runCmd($cmd) == 0) {
            return true;
        } else {
            return false;
        }
        
    }
}
