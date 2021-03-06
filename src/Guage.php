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

    /**
     * 
     * @param string $body
     * @return \microuser\Dialog4Php\Guage
     */
    public function setBody($body){
        return $this->setTypeArgs($body);
    }
    
    /**
     * 
     * @return string
     */
    private function getBody(){
        return parent::getTypeArgs();
    }
    
    /**
     * Floats are fractions between 0 and 1: Where "50/100" is passed as 0.5 is converted to 50
     * @param int|float $percent
     */
    public function setPercent($percent){
        if(is_float($percent)){
            $this->percent = (int)round($percent*100,1);
        } else {
            $this->percent = (int)$percent;
        }
        return $this;
        
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
    
    /**
     * 
     */
    public function start() {
        $cmd = $this->generateProgram();
        $cmd .= $this->generateTitle();
        $cmd .= $this->generateBackTitle();
        $cmd .= $this->generateColorTheme();
        $cmd .= $this->generateType();
        $cmd .= $this->generateScreen();
        $this->processStart($cmd, true);
        return $this;
    }

    /**
     * 
     * @param float|int|null $percent
     */
    public function update($percent = null) {
        if(!is_null($percent)){
            $this->setPrecent($percent);
        }
        fwrite($this->pipes[0], "XXX\n".$this->percent. "\n".$this->getTypeArgs()."\nXXX\n");
        return $this;
    }

    /**
     * 
     * @return bool LastReturnStatus
     */
    public function stop() {
        $this->processStop();
        return $this->getLastReturnStatus();
    }
    
}
