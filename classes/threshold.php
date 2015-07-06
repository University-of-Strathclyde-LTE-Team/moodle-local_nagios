<?php

namespace local_nagios;

class threshold {

    const OUTSIDE = 0;
    const INSIDE = 1;

    public $start;
    public $startinfinity = false;
    public $end;
    public $endinfinity = false;
    public $alerton = self::OUTSIDE;

    public function __construct($start = 0, $end = 0, $alerton = self::OUTSIDE) {
        $this->start = $start;
        $this->end = $end;
        $this->alerton = $alerton;

        if ($start == -INF) {
            $this->startinfinity = true;
        }

        if ($end == INF) {
            $this->endinfinity = true;
        }

    }

    /**
     * Should the given value raise an alert?
     *
     * This is ported from https://github.com/Elbandi/nagios-plugins/blob/master/lib/utils_base.c
     *
     * @param float $value
     */
    public function check($value) {

        $no = false;
        $yes = true;

        if ($this->alerton == self::INSIDE) {
            $no = true;
            $yes = false;
        }

        if (!$this->endinfinity && !$this->startinfinity) {
            if ($this->start <= $value && $value <= $this->end) {
                return $no;
            } else {
                return $yes;
            }
        } else if (!$this->startinfinity && $this->endinfinity) {
            if ($this->start <= $value) {
                return $no;
            } else {
                return $yes;
            }
        } else if ($this->startinfinity && !$this->endinfinity) {
            if ($value <= $this->end) {
                return $no;
            } else {
                return $yes;
            }
        } else {
            return $no;
        }


    }

    public static function from_string($string) {
        $result = new threshold();

        if ($string[0] == '@') {
            $result->alerton = threshold::INSIDE;
            $string = substr($string, 1);
        }

        $endstr = strstr($string, ':');
        if ($endstr !== false) {
            if ($string[0] == '~') {
                $result->startinfinity = true;
            } else {
                $start = floatval($string);
                $result->start = $start;
            }
            $endstr = substr($endstr, 1);
        } else {
            $endstr = $string;
        }

        $end = floatval($endstr);
        if (!empty($endstr)) {
            $result->end = $end;
        }

        if ($result->startinfinity || $result->endinfinity || $result->start <= $result->end) {
            return $result;
        }

        return;
    }

}