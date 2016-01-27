<?php

namespace microuser\Dialog4Php;

/**
 * Description of Dialog4Php
 *
 * @author microuser@github.com
 */
class Dialog4Php {

    const D4P_THEME_DEFAULT = 0;
    const D4P_THEME_SUCCESS = 1;
    const D4P_THEME_OK = 2;
    const D4P_THEME_INFO = 3;
    const D4P_THEME_WARNING = 4;
    const D4P_THEME_CRITICAL = 5;

    /**
     *
     * @var string
     */
    private $backTitle = '';

    /**
     *
     * @var array
     */
    private $colorTheme = array();

    /**
     *
     * @var int
     */
    private $errorCount = 0;

    /**
     *
     * @var bool
     */
    private $escapeKeyReturn = false;

    /**
     *
     * @var int
     */
    private $exitCode = -1;

    /**
     *
     * @var string
     */
    private $lastCommand = '';

    /**
     *
     * @var string
     */
    protected $pipeRedirect = '--output-fd 3';

    /**
     *
     * @var array
     */
    protected $pipes = array();

    /**
     *
     * @var int
     */
    private $processId = null;

    /**
     *
     * @var sring
     */
    protected $program = 'dialog';

    /**
     *
     * @var string
     */
    private $response = '';

    /**
     *
     * @var int
     */
    private $screenHeight = 24;

    /**
     *
     * @var int
     */
    private $screenWidth = 80;

    /**
     *
     * @var bool
     */
    private $shouldExitOnError = true;

    /**
     *
     * @var string
     */
    private $title = '';

    /**
     *
     * @var type
     */
    protected $type;

    /**
     *
     * @var string
     */
    private $typeArgs = '';

    /**
     * 
     */
    private $dryRun = false;
    
    /**
     * 
     * @param string $unescaped
     * @return string
     */
    protected static function escapeSingleQuote($unescaped) {
        //Bash does not use back-slash to escape the single quote, but php does
        return str_replace("'", "'\"'\"'", (string) $unescaped); //Escape the escape character
    }

    /**
     * 
     * @return string
     */
    protected function generateBackTitle() {
        if (!empty($this->getBackTitle())) {
            return " --backtitle '" . $this->getColorTheme('backtitle') . $this->getBackTitle() . "'";
        }
        return '';
    }

    /**
     * 
     * @return type
     */
    protected function generateColorTheme() {
        return (empty($this->getColorTheme('enable')) ? '' : " " . $this->getColorTheme('enable'));
    }

    /**
     * 
     * @return string
     */
    protected function generateProgram() {
        return $this->program . ' ' . $this->pipeRedirect;
    }

    /**
     * 
     * @return string
     */
    protected function generateScreen($modX = 0, $modY = 0) {
        return " " . (((int) $this->getScreenHeight()) - (int) $modY - (empty($this->getBackTitle()) ? 4 : 5 )) .
                " " . (((int) $this->getScreenWidth()) - (int) $modX - 4);
    }

    /**
     * 
     * @return string
     */
    protected function generateTitle() {
        if (!empty($this->getTitle())) {
            return " --title '" . $this->getColorTheme('title') . $this->getTitle() . "'";
        }
        return '';
    }

    /**
     * 
     * @return string
     */
    protected function generateType() {
        if (!empty($this->getTypeArgs())) {
            return " " . $this->getType() . " '" . $this->getColorTheme('body') . $this->getTypeArgs() . "'";
        } if(is_null($this->getTypeArgs())){
            return " " . $this->getType() ;
        } else {
            return " " . $this->getType() . " ''";
        }
    }

    /**
     *
     * @return string
     */
    protected function getBackTitle() {
        return (string) $this->backTitle;
    }

    /**
     *
     * @param string $item
     * @return type
     * @throws \Exception
     */
    public function getColorTheme($item) {
        if (!empty($this->colorTheme)) {
            if (isset($this->colorTheme[$item])) {
                return $this->colorTheme[$item];
            } else {
                throw new \Exception($item.' in ColorTheme, expecting string: backtitle, title, body, or enable');
            }
        }
    }

    protected function getExitCode(){
        return (int)$this->exitCode;
    }
    
    /**
     *
     * @return string
     */
    public function getProgram() {
        return (string) $this->program;
    }

    public function getResponse() {
        return $this->response;
    }

    /**
     * 
     * @return int
     */
    protected function getScreenHeight() {
        return (int) $this->screenHeight;
    }

    /**
     * 
     * @return int
     */
    protected function getScreenWidth() {
        return (int) $this->screenWidth;
    }

    /**
     *
     * @return string
     */
    protected function getTitle() {
        return (string) $this->title;
    }

    /**
     * 
     * @return string
     */
    private function getType() {
        return $this->type;
    }

    /**
     *
     * @return string
     */
    protected function getTypeArgs() {
        return (string) $this->typeArgs;
    }

    /**
     *
     * @param string $cmd
     * @param bool $wantinputfd
     * @return \Dialog4Php\Dialog4Php
     */
    protected function processStart($cmd, $wantinputfd = false) {
        $this->lastCommand = $cmd;
        
        if($this->dryRun){
            echo($cmd);
        } else {
            $this->processId = proc_open(
                    $cmd, array(
                0 => ($wantinputfd) ? array('pipe', 'r') : STDIN,
                1 => STDOUT,
                2 => array('pipe', 'w'),
                3 => array('pipe', 'w'),
                //4 => array('pipe', 'r')
                    ), $this->pipes
            );
        }
        return $this;
    }

    /**
     * 
     * @return \Dialog4Php\Dialog4Php
     */
    protected function processStop() {
        if(!$this->dryRun){
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
                fwrite(STDERR, 'Error with error on command: ' . PHP_EOL . $this->lastCommand . '' . PHP_EOL . PHP_EOL);
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
        }
        $this->exitCode = $procStatus['exitcode'];
        
        return $this;
    }

    /**
     *
     * @param string $cmd
     * @return bool
     */
    protected function runCmd($cmd) {

            $this->processStart($cmd);
            $this->processStop();
        

//Note the return value here is the shell return value,
//Where 0 equals sucess without error
        return $this->exitCode;
    }

    /**
     *
     * @param string $backTitle
     */
    public function setBackTitle($backTitle) {
        $this->backTitle = self::escapeSingleQuote($backTitle);
        return $this;
    }

    /**
     *
     * @param type $colorTheme
     * @return type
     */
    public function setColorTheme($colorTheme) {
        if ($colorTheme == 5 || $colorTheme === 'critical') {
            $this->colorTheme = array(
                'backtitle' => "\Z1",
                'title' => "\Z7",
                'body' => "\Z1",
                'enable' => ' --colors'
            );
        } elseif ($colorTheme == 4 || $colorTheme === 'warning') {
            $this->colorTheme = array(
                'backtitle' => "",
                'title' => "",
                'body' => "",
                'enable' => ' --colors'
            );
        } elseif ($colorTheme == 3 || $colorTheme === 'info') {
            $this->colorTheme = array(
                'backtitle' => "",
                'title' => "",
                'body' => "",
                'enable' => ' --colors'
            );
        } elseif ($colorTheme == 2 || $colorTheme === 'ok') {
            $this->colorTheme = array(
                'backtitle' => "",
                'title' => "",
                'body' => "",
                'enable' => ' --colors'
            );
        } elseif ($colorTheme == 1 || $colorTheme === 'success') {
            $this->colorTheme = array(
                'backtitle' => "",
                'title' => "",
                'body' => "",
                'enable' => ' --colors'
            );
        } else {
            $this->colorTheme = array(
                'backtitle' => "",
                'title' => "",
                'body' => "",
                'enable' => ''
            );
        }
        return $this;
    }

    /**
     *
     * @param type $returnValue
     * @return \Dialog4Php
     */
    public function setEscapeKeyReturn($returnValue) {
        $this->escapeKeyReturn = $returnValue;
        return $this;
    }

    /**
     *
     * @param int $toExit
     * @return \Dialog4Php
     */
    public function setExitOnError($toExit) {
        $this->shouldExitOnError = $toExit;
        return $this;
    }

    /**
     *
     * @return \Dialog4Php
     */
    public function setScreen80x24() {
        $this->setScreenWidth(80);
        $this->setScreenHeight(24);
        return $this;
    }

    /**
     *
     * @param int $height
     * @return \Dialog4Php\Dialog4Php
     */
    public function setScreenHeight($height) {
        $this->screenHeight = (int) $height;
        return $this;
    }

    /**
     * Uses dialog to detect the largest screen size
     * @return \Dialog4Php\Dialog4Php
     */
    public function setScreenMax() {
        $output = array();
        $this->runCmd('dialog  --output-fd 3 --print-maxsize ');
        $wxy = explode(" ", str_replace(",", "", $this->response));
        if (isset($wxy[2]) && isset($wxy[1])) {
            $this->setScreenWidth($wxy[2]);
            $this->setScreenHeight($wxy[1]);
        }
        return $this;
    }

    /**
     *
     * @param int $width
     * @return \Dialog4Php\Dialog4Php
     */
    public function setScreenWidth($width) {
        $this->screenWidth = (int) $width;
        return $this;
    }

    /**
     *
     * @param string $title
     * @return \Dialog4Php
     */
    public function setTitle($title) {
        $this->title = self::escapeSingleQuote($title);
        return $this;
    }

    protected function setType(){
        $this->type;
        return $this;
    }
    
    /**
     *
     * @param string $body
     * @return \Dialog4Php
     */
    protected function setTypeArgs($typeArgs) {
        if (is_string($typeArgs)) {
            $this->typeArgs = self::escapeSingleQuote($typeArgs);
        } else if (is_array($typeArgs)) {
            $this->typeArgs = '';
            foreach ($typeArgs as $arg) {
                $this->typeArgs .= self::escapeSingleQuote($arg);
            }
        } else if (is_null($typeArgs)){
            $this->typeArgs = null;
        }
        return $this;
    }

    public function msgBox($body, $title = null, $backTitle = null) {
        $colorThemes = $this->colorTheme($colorTheme);
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
        $colorThemes = $this->colorTheme($colorTheme);
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
        $colorThemes = $this->colorTheme($colorTheme);
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

    public function timeBox($body, $hour = null, $minute = null, $second = null, $title = null, $backtitle = null, $colorTheme = null) {
        $colorThemes = $this->colorTheme($colorTheme);
        $body = " --timebox '" . str_replace("'", "\\'", $colorThemes['title'] . $body) . "'";
        $title = ($title === null) ? null : " --title '" . str_replace("'", "\\'", $colorThemes['title'] . $title) . "'";
        $backtitle = ($backtitle === null) ? null : " --backtitle '" . str_replace("'", "\\'", $colorThemes['backtitle'] . $backtitle) . "'";
        $charHeight = $this->screenHeight - (($backtitle === null) ? 10 : 12);
        $charWidth = $this->screenWidth - 4;
        $this->runCmd("dialog --output-fd 3" . $title . $backtitle . $colorThemes['colors'] . $body . " $charHeight $charWidth $hour $minute $second");
        if ($this->ret == 0) {
            return true;
        } else {
            return false;
        }
    }
    
    

}