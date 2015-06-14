<?php

/**
 * Description of Dialog4Php
 *
 * @author microuser@github.com
 */
class Dialog4Php {

    private $pipes = array();
    private $processId = null;
    private $cout = '';
    private $ret = -1;
    private $errorCount = 0;
    private $shouldExitOnError = true;
    private $escapeKeyReturn = false;

    public function setExitOnError($toExit) {
        $this->shouldExitOnError = $toExit;
    }

    public function setEscapeKeyReturn($returnValue) {
        $this->escapeKeyReturn = $returnValue;
    }

    private function processStart($cmd, $wantinputfd = false) {
        $this->processId = proc_open(
                $cmd, array(
            0 => ($wantinputfd) ? array('pipe', 'r') : STDIN,
            1 => STDOUT,
            2 => array('pipe', 'w'),
            3 => array('pipe', 'w')
                ), $this->pipes
        );
    }

    private function processStop() {
        if (isset($this->pipes[0])) {
            fclose($this->pipes[0]);
            usleep(2000);
        }

        $this->cout = '';
        while ($partial = fgets($this->pipes[3])) {
            $this->cout = $partial;
        }

        while ($partial = fgets($this->pipes[2])) {
            fwrite(STDERR, $partial);
            ++$this->errorCount;
        }

        if ($this->errorCount && $this->shouldExitOnError) {
            fwrite(STDERR, 'Program exiting with error');
            exit(1);
        }

        fclose($this->pipes[2]);
        fclose($this->pipes[3]);

        //Loop while process is still running
        do {
            usleep(2000);
            $procStatus = proc_get_status($this->processId);
        } while ($procStatus['running']);

        proc_close($this->processId);
        $this->ret = $procStatus['exitcode'];
    }

    public function runCmd($cmd) {
        $this->processStart($cmd);
        $this->processStop();

        //Note the return value here is the shell return value,
        //Where 0 equals sucess without error
        return $this->ret;
    }

    public function infoBox($body, $title = null, $charHeight = 20, $charWidth = 74) {
        $body = "--infobox '" . str_replace("'", "\\'", $body) . "'";
        $title = "--title '" . str_replace("'", "\\'", $title) . "'";
        $this->runCmd("dialog $title $body $charHeight $charWidth");

        if ($this->ret == 0) {
            return true;
        } else {
            return false;
        }
    }

    public function msgBox($body, $title = null, $charHeight = 20, $charWidth = 74) {
        $body = "--msgbox '" . str_replace("'", "\\'", $body) . "'";
        $title = "--title '" . str_replace("'", "\\'", $title) . "'";
        return $this->runCmd("dialog $title $body $charHeight $charWidth");
        if ($this->ret == 0) {
            return true;
        } else {
            return false;
        }
    }

    public function yesnoBox($body, $title = null, $charHeight = 20, $charWidth = 74) {
        $body = "--yesno '" . str_replace("'", "\\'", $body) . "'";
        $title = "--title '" . str_replace("'", "\\'", $title) . "'";
        $this->runCmd("dialog $title $body $charHeight $charWidth");

        if ($this->ret == 0) {
            return true;
        } else {
            return false;
        }
    }
    
    

}
