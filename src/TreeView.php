<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace microuser\Dialog4Php;
use microuser\Dialog4Php\Dialog4Php;

/**
 * Description of TreeView
 *
 * @author user
 */
class TreeView extends Dialog4Php{
    
    protected $type = '--treeview';
    
    public function setBody($body){
        return $this->setTypeArgs($body);
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
                    $string .= " '" . $trees[0] . "'" . " '" . $trees[1] . "'" . " '" . $trees[2] . "'" . " $depth";
                }
            }
        }
        //}
        return $string;
    }
    
    public function run(){
        
        
        $cmd = $this->generateType();
        $cmd .= $this->generateTitle();
        $cmd .= $this->generateBackTitle();
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
