<?php

namespace microuser\Dialog4Php;
use microuser\Dialog4Php\Dialog4Php;

class InfoBox extends Dialog4PHP {

    /**
     *
     * @var string
     */
    protected $type = "--infobox";

    /**
     *
     * @param string $body
     * @return \Dialog4Php\InfoBox
     */
    public function setBody($body) {
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
        $cmd .= $this->generateScreen(0, 0);
        if ($this->runCmd($cmd) == 0) {
            return true;
        } else {
            return false;
        }
    }

}
