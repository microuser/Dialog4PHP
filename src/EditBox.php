<?php

namespace Dialog4Php;

class EditBox extends Dialog4PHP {

    /**
     *
     * @var string
     */
    protected $type = "--editbox";

    /**
     *
     * @param string $filePath
     * @return \Dialog4Php\EditBox
     */
    public function setFilePath($filePath) {
        $this->setTypeArgs($filePath);
        return $this;
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
        if ($this->runCmd($cmd) == 0) {
            return true;
        } else {
            return false;
        }
    }

}