<?php

namespace Dialog4Php;

require_once('Dialog4PhpAbstract.php');
require_once('D4P_InfoBox.php');

/**
 * Description of Dialog4Php
 *
 * @author microuser@github.com
 */
class Dialog4Php {

    /**
     *
     * @var array
     */
    private $pipes = array();

    /**
     *
     * @var int
     */
    private $processId = null;

    /**
     *
     * @var string
     */
    private $response = '';

    /**
     *
     * @var int
     */
    private $exitCode = -1;

    /**
     *
     * @var int
     */
    private $errorCount = 0;

    /**
     *
     * @var bool
     */
    private $shouldExitOnError = true;

    /**
     *
     * @var bool
     */
    private $escapeKeyReturn = false;

    /**
     *
     * @var int
     */
    protected $screenHeight = 24;

    /**
     *
     * @var int
     */
    protected $screenWidth = 80;

    /**
     *
     * @var string
     */
    private $lastCommand = '';

    /**
     *
     * @var type 
     */
    protected $type = null;

    const D4P_THEME_DEFAULT = 0;
    const D4P_THEME_CRITICAL = 1;
    const D4P_THEME_WARNING = 2;
    const D4P_THEME_INFO = 3;
    const D4P_THEME_OK = 4;
    const D4P_THEME_SUCCESS = 5;

    private $colorTheme = array();

    /**
     *
     * @var string 
     */
    private $body = '';

    /**
     *
     * @var string 
     */
    private $title = '';

    /**
     *
     * @var string 
     */
    private $backTitle = '';
    private $program = 'dialog';

    private function getType(){
        
        return $this->type;
    }
    
    protected function getScreenWidth() {
        return (int) $this->screenWidth;
    }

    protected function getScreenHeight() {
        return (int) $this->screenHeight;
    }

    /**
     * 
     * @return string
     */
    public function getProgram() {
        return (string) $this->program;
    }

    /**
     * 
     * @param type $width
     * @param type $height
     * @return \Dialog4Php
     */
    public function setXY($width, $height) {
        $this->screenHeight = $height;
        $this->screenWidth = $width;
        return $this;
    }

    /**
     * 
     * @return \Dialog4Php
     */
    public function resetXY() {
        $this->setXY(80, 24);
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

    public function __construct() {
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
     * @param type $cmd
     * @param type $wantinputfd
     */
    protected function processStart($cmd, $wantinputfd = false) {
        $this->lastCommand = $cmd;
        $this->processId = proc_open(
                $cmd, array(
            0 => ($wantinputfd) ? array('pipe', 'r') : STDIN,
            1 => STDOUT,
            2 => array('pipe', 'w'),
            3 => array('pipe', 'w')
                ), $this->pipes
        );
    }

    /**
     * Closes the process id
     */
    protected function processStop() {
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
        $this->exitCode = $procStatus['exitcode'];
    }

    /**
     * 
     * @param type $cmd
     * @return type
     */
    protected function runCmd($cmd) {
        echo $cmd;
        $this->processStart($cmd);
        $this->processStop();

        //Note the return value here is the shell return value,
        //Where 0 equals sucess without error
        return $this->exitCode;
    }

    /**
     * 
     * @param string $item
     * @return type
     * @throws \Exception
     */
    public function getColorTheme($item) {
        if ($this->colorTheme !== null) {
            if (isset($this->colorTheme[$item])) {
                return $this->colorTheme[$item];
            } else {
                throw new \Exception('Key not in array, expecting string: backtitle, title, body, or enable');
            }
        }
    }

    /**
     * 
     * @param type $colorTheme
     * @return type
     */
    public function setColorTheme($colorTheme) {
        if ($colorTheme == 1 || $colorTheme === 'critical') {
            $this->colorTheme = array(
                'backtitle' => "\Z1",
                'title' => "\Z7",
                'body' => "\Z1",
                'enable' => ' --colors'
            );
        } elseif ($colorTheme == 2 || $colorTheme === 'warning') {
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
        } elseif ($colorTheme == 4 || $colorTheme === 'ok') {
            $this->colorTheme = array(
                'backtitle' => "",
                'title' => "",
                'body' => "",
                'enable' => ' --colors'
            );
        } elseif ($colorTheme == 5 || $colorTheme === 'success') {
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
     * Double escaped because php escapes the escape, then the shell escapes the single quote
     * @param string $unescaped
     * @return string
     */
    static private function escapeSingleQuote($unescaped) {
        return (string) str_replace("'", "\\'", $unescaped);
    }

    /**
     * 
     * @param string $body
     * @return \Dialog4Php
     */
    public function setBody($body) {
        $this->body = self::escapeSingleQuote($body);
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
     * @return string
     */
    protected function getBody() {
        return (string) $this->body;
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
    protected function getBackTitle() {
        return (string) $this->backTitle;
    }

    public function getInfoBox() {
        return new D4P_InfoBox();
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

    public function guageStart($body, $title = null, $backtitle = null, $colorTheme = null) {
        $colorThemes = $this->colorTheme($colorTheme);
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

    public function getResponse() {
        return $this->response;
    }

    public function editBox($filePath, $title = null, $backtitle = null, $colorTheme = null) {
        $colorThemes = $this->colorTheme($colorTheme);
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

    protected function generateColorTheme(){
        return $this->getColorTheme('enable');
    }
    
    protected function generateTitle() {
        if (!empty($this->getTitle())) {
            return " --title '" . $this->getColorTheme('title') . $this->getTitle() . "'";
        }
        return '';
    }

    protected function generateBackTitle() {
        if (!empty($this->getBackTitle())) {
            return " --backtitle '" . $this->getColorTheme('backtitle') . $this->getBackTitle() . "'";
        }
        return '';
    }

    protected function generateBody() {
        if (!empty($this->getBody())) {
            return $this->generateType() . " '" . $this->getColorTheme('body') . $this->getBody() . "'";
        }
        return "''";
    }

    protected function generateType() {
        return ' ' . $this->getType();
    }

}
