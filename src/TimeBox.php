<?php

namespace microuser\Dialog4Php;

use microuser\Dialog4Php\Dialog4Php;

class TimeBox extends Dialog4PHP {

    /**
     *
     * @var string
     */
    protected $type = "--timebox";

    /**
     *
     * @var int
     */
    private $hour = null;

    /**
     *
     * @var int
     */
    private $minute = null;

    /**
     *
     * @var int
     */
    private $second = null;

    /**
     * 
     * @param int $hour
     * @return \microuser\Dialog4Php\Pause
     */
    public function setHour($hour) {
        $this->hour = ((int) $hour) % 24;
        return $this;
    }

    /**
     * 
     * @param int $minute
     * @return \microuser\Dialog4Php\Pause
     */
    public function setMinute($minute) {
        $this->minute = ((int) $minute % 60);
        return $this;
    }

    /**
     * 
     * @param int $second
     * @return \microuser\Dialog4Php\Pause
     */
    public function setSecond($second) {
        $this->second = ((int) $second % 60);
        return $this;
    }

    /**
     * 
     * @param array|string|int $time
     * @param string $timeZone
     * @return \microuser\Dialog4Php\TimeBox
     * @throws \Exception
     */
    public function setTime($time, $timeZone = 'UTC') {
        if (is_array($time) && isset($time['hour']) && isset($time['minute']) && isset($time['second'])) {
            $this->setHour($time['hour']);
            $this->setMinute($time['minute']);
            $this->setSecond($time['second']);
        } elseif (is_array($time) && isset($time[0]) && isset($time[1]) && isset($time[2])) {
            $this->setHour($time[0]);
            $this->setMinute($time[0]);
            $this->setSecond($time[0]);
        } elseif (is_string($time)) {
            if (preg_match("/^[0-4][0-9]:[0-9][0-9]:[0-9][0-9]( [APap][mM])?$/", $time)) {
                $hour = 0;
                $parts = explode(" ", $time);
                if (isset($parts[1])) {
                    if (preg_match("/^ [pP][mM]$/", $parts[1])) {
                        $hour = 12;
                    }
                }
                if (isset($parts[0])) {
                    $hms = explode(":", $parts[0]);
                    $this->setHour($hms[0]+$hour);
                    $this->setMinute($hms[1]);
                    $this->setSecond($hms[2]);
                }
            }
        } elseif (is_int($time)) {
            date_default_timezone_set($timeZone);
            $this->setHour(date("h", $time));
            $this->setMinute(date("i", $time));
            $this->setSecond(date("s", $time));
        } else{
            throw new \Exception("Invalid Timeformat: Many formats available, none matched");
        }
        return $this;
    }

    /**
     * 
     * @param string $body
     * @return string
     */
    public function setBody($body) {
        return $this->setTypeArgs($body);
    }

    /**
     * 
     * @return string
     */
    public function getBody() {
        return $this->getTypeArgs();
    }

    public function generateTime(){
        return ' '.$this->hour.' '.$this->minute.' '.$this->second;
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
        $cmd .= $this->generateTime();
        return $this->runCmd($cmd);
    }

}
