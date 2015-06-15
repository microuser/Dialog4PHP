<?php

/**
 * Description of Dialog4Php
 *
 * @author microuser@github.com
 */
class Dialog4Php {

    private $pipes = array();
    private $processId = null;
    private $response = '';
    private $ret = -1;
    private $errorCount = 0;
    private $shouldExitOnError = true;
    private $escapeKeyReturn = false;
    private $screenHeight = 24;
    private $screenWidth = 80;

    public function setXY($width, $height) {
        $this->screenHeight = $height;
        $this->screenWidth = $width;
        return $this;
    }

    public function resetXY() {
        $this->setXY(80, 24);
        return $this;
    }

    public function setExitOnError($toExit) {
        $this->shouldExitOnError = $toExit;
        return $this;
    }

    public function setEscapeKeyReturn($returnValue) {
        $this->escapeKeyReturn = $returnValue;
        return $this;
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

        $this->response = '';
        while ($partial = fgets($this->pipes[3])) {
            $this->response .= $partial;
        }

        while ($partial = fgets($this->pipes[2])) {
            fwrite(STDERR, $partial);
            ++$this->errorCount;
        }

        if ($this->errorCount && $this->shouldExitOnError) {
            fwrite(STDERR, 'Program exiting with error' . PHP_EOL);
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

    private function colorThemes($colorTheme) {

        if ($colorTheme == 1 || $colorTheme === 'critical') {
            return array(
                'backtitle' => "\Z1",
                'title' => "\Z7",
                'body' => "\Z1",
                'colors' => ' --colors'
            );
        } elseif ($colorTheme == 2 || $colorTheme === 'warning') {
            return array(
                'backtitle' => "",
                'title' => "",
                'body' => "",
                'colors' => ' --colors'
            );
        } elseif ($colorTheme == 3 || $colorTheme === 'info') {
            return array(
                'backtitle' => "",
                'title' => "",
                'body' => "",
                'colors' => ' --colors'
            );
        } elseif ($colorTheme == 4 || $colorTheme === 'ok') {
            return array(
                'backtitle' => "",
                'title' => "",
                'body' => "",
                'colors' => ' --colors'
            );
        } elseif ($colorTheme == 5 || $colorTheme === 'success') {
            return array(
                'backtitle' => "",
                'title' => "",
                'body' => "",
                'colors' => ' --colors'
            );
        }
        return array(
            'backtitle' => "",
            'title' => "",
            'body' => "",
            'colors' => ''
        );
    }

    public function infoBox($body, $title = null, $backtitle = null, $colorTheme = null) {

        $colorThemes = $this->colorThemes($colorTheme);
        $body = " --infobox '" . str_replace("'", "\\'", $colorThemes['body'] . $body) . "'";
        $title = ($title === null) ? null : " --title '" . str_replace("'", "\\'", $colorThemes['title'] . $title) . "'";
        $backtitle = ($backtitle === null) ? null : " --backtitle '" . str_replace("'", "\\'", $colorThemes['backtitle'] . $backtitle) . "'";
        $charHeight = $this->screenHeight - (($backtitle === null) ? 3 : 5);
        $charWidth = $this->screenWidth - 4;
        $this->runCmd("dialog --output-fd 3" . $title . $backtitle . $colorThemes['colors'] . $body . " $charHeight $charWidth");
        if ($this->ret == 0) {
            return true;
        } else {
            return false;
        }
    }

    public function msgBox($body, $title = null, $backtitle = null) {
        $colorThemes = $this->colorThemes($colorTheme);
        $body = " --msgbox '" . str_replace("'", "\\'", $colorThemes['body'] . $body) . "'";
        $title = ($title === null) ? null : " --title '" . str_replace("'", "\\'", $colorThemes['title'] . $title) . "'";
        $backtitle = ($backtitle === null) ? null : " --backtitle '" . str_replace("'", "\\'", $colorThemes['backtitle'] . $backtitle) . "'";
        $charHeight = $this->screenHeight - (($backtitle === null) ? 3 : 5);
        $charWidth = $this->screenWidth - 4;
        $this->runCmd("dialog --output-fd 3" . $title . $backtitle . $colorThemes['colors'] . $body . " $charHeight $charWidth");
        if ($this->ret == 0) {
            return true;
        } else {
            return false;
        }
    }

    public function yesnoBox($body, $title = null, $backtitle = null, $colorTheme = null) {
        $colorThemes = $this->colorThemes($colorTheme);
        $body = " --yesno '" . str_replace("'", "\\'", $body) . "'";
        $title = ($title === null) ? null : " --title '" . str_replace("'", "\\'", $colorThemes['title'] . $title) . "'";
        $backtitle = ($backtitle === null) ? null : " --backtitle '" . str_replace("'", "\\'", $colorThemes['backtitle'] . $backtitle) . "'";
        $charHeight = $this->screenHeight - (($backtitle === null) ? 3 : 5);
        $charWidth = $this->screenWidth - 4;
        $this->runCmd("dialog --output-fd 3" . $title . $backtitle . $colorThemes['colors'] . $body . " $charHeight $charWidth");

        if ($this->ret == 0) {
            return true;
        } else {
            return false;
        }
    }

    public function guageStart($body, $title = null, $backtitle = null, $colorTheme = null) {
        $colorThemes = $this->colorThemes($colorTheme);
        $this->guageBody(str_replace("'", "\\'", $colorThemes['body'] . $body));
        $body = " --guage '" . str_replace("'", "\\'", $body) . "'";

        $title = ($title === null) ? null : " --title '" . str_replace("'", "\\'", $colorThemes['title'] . $title) . "'";
        $backtitle = ($backtitle === null) ? null : " --backtitle '" . str_replace("'", "\\'", $colorThemes['backtitle'] . $backtitle) . "'";
        $charHeight = $this->screenHeight - (($backtitle === null) ? 3 : 5);
        $charWidth = $this->screenWidth - 4;
        $this->processStart("dialog --output-fd 3" . $title . $backtitle . $colorThemes['colors'] . $body . " $charHeight $charWidth", true);

        if ($this->ret == 0) {
            return true;
        } else {
            return false;
        }
    }

    public function guageBody($body = null) {
        static $bodyOld = '';
        if ($body !== null) {
            $bodyOld = $body;
        }
        return $bodyOld;
    }

    public function guageUpdate($percent) {
        fwrite($this->pipes[0], "XXX\n" . $percent . "\n" . $this->guageBody() . "\nXXX\n" . ($percent) . "\n");
    }

    public function guageStop() {
        $this->processStop();
        return $this->ret[0];
    }

    public function inputBox($body, $default) {
        $default = ($default === null) ? null : "'" . str_replace("'", "\\'", $colorThemes['body'] . $default) . "'";
        $colorThemes = $this->colorThemes($colorTheme);
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

    public function getResponse() {
        return $this->response;
    }

    public function editBox($filePath, $title = null, $backtitle = null, $colorTheme = null) {
        $colorThemes = $this->colorThemes($colorTheme);
        $body = " --editbox '" . str_replace("'", "\\'", $filePath) . "'";
        $title = ($title === null) ? null : " --title '" . str_replace("'", "\\'", $colorThemes['title'] . $title) . "'";
        $backtitle = ($backtitle === null) ? null : " --backtitle '" . str_replace("'", "\\'", $colorThemes['backtitle'] . $backtitle) . "'";
        $charHeight = $this->screenHeight - (($backtitle === null) ? 3 : 5);
        $charWidth = $this->screenWidth - 4;
        $this->runCmd("dialog --output-fd 3" . $title . $backtitle . $colorThemes['colors'] . $body . " $charHeight $charWidth");
        if ($this->ret == 0) {
            return true;
        } else {
            return false;
        }
    }

    private function recursiveTree($trees, $depth = -1) {
        /*
         * array(
         *     1 1 1
         *     2 2 2
         *     3 3 3
         *     array(
         *         3.1 3.1 3.1
         *         3.2 3.2 3.2 
         *         3.3 3.3 3.3
         *         array(
         *             3.3.1 3.3.1 3.3.1
         *         )
         *         3.4 3.4 3.4 3.4
         *     )
         *     4 4 4
         */

        $string = '';
        //if (is_array($trees)) {
            foreach ($trees as $key => $tree) {
                if (is_array($tree) && !is_string($tree)) {
                    $string .= $this->recursiveTree($tree, $depth + 1);
                }
                //We are at end of branch
                else {
                    if ($key == 2) {
                        $string .= " '" . $trees[0] . "'" . " '" . $trees[1] . "'" . " '" . $trees[2] . "'" . " $depth";
                    }
                }
            }
        //}
        return $string;
    }

    public function treeView($body, $listHeight, Array $tree, $title = null, $backtitle = null, $colorTheme = null) {
        $treeString = $this->recursiveTree($tree);
        $colorThemes = $this->colorThemes($colorTheme);
        $body = " --treeview '" . str_replace("'", "\\'", $colorThemes['title'] . $body) . "'";
        $title = ($title === null) ? null : " --title '" . str_replace("'", "\\'", $colorThemes['title'] . $title) . "'";
        $backtitle = ($backtitle === null) ? null : " --backtitle '" . str_replace("'", "\\'", $colorThemes['backtitle'] . $backtitle) . "'";
        $charHeight = $this->screenHeight - (($backtitle === null) ? 3 : 5);
        $charWidth = $this->screenWidth - 4;
        $this->runCmd("dialog --output-fd 3" . $title . $backtitle . $colorThemes['colors'] . $body . " $charHeight $charWidth $listHeight $treeString");
        if ($this->ret == 0) {
            return true;
        } else {
            return false;
        }
    }

}
