<?php

namespace Dialog4Php;

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
    
    public function run(){
        $this->program = "echo '".$this->percent."' | dialog";
        $cmd = $this->generateProgram();
        $cmd .= $this->generateTitle();
        $cmd .= $this->generateBackTitle();
        $cmd .= $this->generateColorTheme();
        $cmd .= $this->generateType();
        $cmd .= $this->generateScreen();
        $this->runCmd($cmd);
        
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
        if(is_float($percent)){
            $this->percent = (int)($percent * 100);
        }
        if($percent !== null){
            echo "$percent";
            $this->percent = $percent;
        }
        fwrite($this->pipes[0], "\n".$this->percent. "\n");
    }

    public function stop() {
        $this->processStop();
        return $this->getExitCode();
    }
    
}
