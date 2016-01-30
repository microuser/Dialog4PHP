<?php

namespace microuser\Dialog4Php;
use microuser\Dialog4Php\Dialog4Php;

/**
 * Description of TreeView
 *
 * @author user
 */
class TreeView extends Dialog4Php{
    
    /**
     *
     * @var string
     */
    protected $type = '--treeview';
    
    /**
     *
     * @var int
     */
    private $listHeight = null;
    
    /**
     *
     * @var string
     */
    private $body = '';
    /**
     *
     * @var array
     */
    private $tree = array();

    private $treeItemCount = 0;
    
    /**
     * 
     * @return string
     */
    public function getBody(){
        return $this->getTypeArgs();
    }
    
    /**
     * 
     * @param string $body
     * @return \microuser\Dialog4Php\TreeView
     */
    public function setBody($body){
        return $this->setTypeArgs($body);
    }
    
    /**
     * 
     * @param array|string $arrayOrString
     * @return \microuser\Dialog4Php\TreeView
     */
    public function appendTreeRoot($arrayOrString){
        $this->tree[] = $array;
        return $this;
    }
    
    /**
     * 
     * @param string $tree
     * @return \microuser\Dialog4Php\TreeView
     */
    public function setTree($tree){
        $this->tree = $tree;
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getTree(){
        return $this->tree;
    }
    
    
    
        
    
    /**
     * 
     * @param type $trees
     * @param type $depth
     * @return string
     */
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
                    $this->treeItemCount += 1;
                    $string .= " '" . $this->escapeSingleQuote($trees[0]) . "'" . " '" . $this->escapeSingleQuote($trees[1]) . "'" . " '" . $this->escapeSingleQuote($trees[2]) . "'" . " '$depth'";
                }
            }
        }
        //}
        return $string;
    }
    
    /**
     * If $this->listHeight is null, then it calcualtes the height from the elemnets in $this->tree
     * If $this->listHeight is int, then it uses the manually set value. 
     * Set with $this->setListHeight($height)
     * @return int
     */
    public function getListHeight(){
        if($this->listHeight === null){
            return $this->treeItemCount;
        }
        return (int)$this->listHeight;
    }
    
    /**
     * 
     * Allows setting of the list height. 
     * If passed a null, then height is automatically detected from list
     * @param int|null $height
     * @return \microuser\Dialog4Php\TreeView
     */
    public function setListHeight($height){
        $this->listHeight = (int)$height;
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function generateTree(){
        $this->treeItemCount = 0;
        $treeString = $this->recursiveTree($this->getTree());
        return ' '.$this->getListHeight().' '.$treeString;
    }
    
    public function run(){
        $cmd = $this->generateProgram();
        $cmd .= $this->generateTitle();
        $cmd .= $this->generateBackTitle();
        $cmd .= $this->generateColorTheme();
        $cmd .= $this->generateType();
        $cmd .= $this->generateScreen();
        $cmd .= $this->generateTree();
        return $this->runCmd($cmd);
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
}
