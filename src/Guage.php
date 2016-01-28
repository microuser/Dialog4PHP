<?php

namespace microuser\Dialog4Php;
use microuser\Dialog4Php\Dialog4Php;

class Guage extends Dialog4PHP {

    /**
     *
     * @var string
     */
    protected $type = "--guage";
    
    /**
     *
     * @var int 
     */
    private $percent = 0;

    public function setBody($body){
        parent::setTypeArgs($body);
        return $this;
    }
    
    private function getBody(){
        return parent::getTypeArgs();
    }
    
    public function setPercent($percent){
        $this->percent = $percent;
    }
    
    /**
     * 
     * @return bool return status of runCmd (currently always true)
     */
    public function run(){
        //TODO Feature Request: Ability to cancel a progress bar with escape key
        $this->program = "echo '".$this->percent."' | dialog";
        $cmd = $this->generateProgram();
        $cmd .= $this->generateTitle();
        $cmd .= $this->generateBackTitle();
        $cmd .= $this->generateColorTheme();
        $cmd .= $this->generateType();
        $cmd .= $this->generateScreen();
        return $this->runCmd($cmd);
        
    }
    
    public function start() {
        $cmd = $this->generateProgram();
        $cmd .= $this->generateTitle();
        $cmd .= $this->generateBackTitle();
        $cmd .= $this->generateColorTheme();
        $cmd .= $this->generateType();
        $cmd .= $this->generateScreen();
        $this->processStart($cmd, true);
    }

    public function update($percent = null) {
        //Floats are fractions between 0 and 1: 0 < X < 1
        if(is_float($percent)){
            $this->percent = (int)($percent * 100);
        }
        //Ints are percents
        //Nulls don't change anything
        if($percent !== null){
            $this->percent = $percent;
        }
        fwrite($this->pipes[0], "XXX\n".$this->percent. "\n".$this->getTypeArgs()."\nXXX\n");
    }

    public function stop() {
        $this->processStop();
        return $this->getLastReturnStatus();
    }
    
}
