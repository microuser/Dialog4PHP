<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace microuser\Dialog4Php;

use microuser\Dialog4Php\Dialog4Php;

/**
 * Description of InputBox
 *
 * @author user
 */
class InputBox extends Dialog4Php {

    /**
     *
     * @var string
     */
    protected $type = '--inputbox';

    /**
     *
     * @var string
     */
    private $input = '';

    /**
     * 
     * @param string $input
     * @return \microuser\Dialog4Php\InputBox
     */
    public function setInput($input) {
        $this->input = parent::escapeSingleQuote($input);
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    private function getInput(){
        return (string)$this->input;
    }

    /**
     * 
     * @return string
     */
    private function generateInput() {
        if (!empty($this->input)) {
            return " '" .$this->getInput(). "'";
        } else {
            return " ''";
        }
    }

    /**
     * 
     * @param string $body
     * @return \microuser\Dialog4Php\InputBox
     */
    public function setBody($body) {
        return $this->setTypeArgs($body);
    }

    public function run() {
        $cmd = $this->generateProgram();
        $cmd .= $this->generateTitle();
        $cmd .= $this->generateBackTitle();
        $cmd .= $this->generateColorTheme();
        $cmd .= $this->generateType();
        $cmd .= $this->generateScreen(0, 0);
        $cmd .= $this->generateInput();
        return $this->runCmd($cmd);
    }

    public function inputBox($body, $default) {
        $default = ($default === null) ? null : "'" . str_replace("'", "\\'", $colorThemes['body'] . $default) . "'";
        $colorThemes = $this->colorTheme($colorTheme);
        $body = " --inputbox '" . str_replace("'", "\\'", $colorThemes['body'] . $body) . "'";
        $title = ($title === null) ? null : " --title '" . str_replace("'", "\\'", $colorThemes['title'] . $title) . "'";
        $backtitle = ($backtitle === null) ? null : " --backtitle '" . str_replace("'", "\\'", $colorThemes['backtitle'] . $backtitle) . "'";
        $charHeight = $this->screenHeight - (($backtitle === null) ? 3 : 5);
        $charWidth = $this->screenWidth - 4;
        $this->runCmd("dialog --output-fd 3" . $title . $backtitle . $colorThemes['colors'] . $body . " $charHeight $charWidth $default");
        if ($this->ret == 0) {
            return true;
        } else {
            return false;
        }
    }

}
