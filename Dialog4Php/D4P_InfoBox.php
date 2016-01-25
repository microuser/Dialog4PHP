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
class D4P_InfoBox extends Dialog4PHP{// implements Dialog4PhpAbstract {

    protected $type = "--infobox";

    public function run() {
        $cmd = $this->getProgram();
        $cmd .= ' --output-fd 3';
        $cmd .= $this->generateTitle();
        $cmd .= $this->generateBackTitle();
        $cmd .= $this->generateColorTheme();
        $cmd .= $this->generateBody();
        $cmd .= ' ' . (((int)$this->screenHeight) - ( (empty($this->getBackTitle()) ? 3 : 5 ) ));
        $cmd .= ' ' . (((int)$this->screenWidth) - 4);
        if ($this->runCmd($cmd) == 0) {
            return true;
        } else {
            return false;
        }
        
    }
}
