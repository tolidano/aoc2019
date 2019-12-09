<?php
include_once('Functions.php');

class Computer {
    const STP = 99;
    const ADD = 1;
    const MUL = 2;
    const STO = 3;
    const OUT = 4;
    const JIT = 5;
    const JIF = 6;
    const LT = 7;
    const EQ = 8;
    const REL = 9;
    const GT = 11;
    const NEQ = 10;

    const SIZES = [
        self::STP => 1,
        self::ADD => 4,
        self::MUL => 4,
        self::STO => 2,
        self::OUT => 2,
        self::JIT => 3,
        self::JIF => 3,
        self::LT => 4,
        self::EQ => 4,
        self::GT => 4,
        self::NEQ => 4,
        self::REL => 2,
    ];

    public $ram = [];
    public $outputs = [];
    public $inputs = [];
    public $ip = 0;
    public $rel = 0;

    public function res($file) {
        $dump = trim(fgc($file));
        $ram = ex(',', $dump);
        foreach ($ram as &$r) {
            $r = (int)$r;
        }
        $this->ram = $ram;
    }

    public function loc($modes, $param) {
        if (l($modes) > $param - 1 && s($modes, -1 * $param, 1) == '1') {
            $loc = $this->ip + $param;
        } elseif (l($modes) > $param - 1 && s($modes, -1 * $param, 1) == '2') {
            $loc = $this->rel + $this->ram[$this->ip + $param];
        } else {
            $loc = $this->ram[$this->ip + $param];
        }
        if (!isset($this->ram[$loc])) {
            $this->ram[$loc] = 0;
        }
        return $loc;
    }

    public function run($inputs = []) {
        $this->inputs = $inputs;
        $inputCount = 0;
        $fop = $this->ram[$this->ip];
        while ($fop != self::STP) {
            $digits = (string)$fop;
            if (l($digits) < 3) {
                $op = (int)$fop;
                $modes = '';
            } else {
                $op = (int)s($digits, -2);
                $modes = s($digits, 0, l($digits) - 2);
            }
            $newPos = -1;
            $jump = self::SIZES[$op];

            if ($jump > 1) {
                $loc1 = $this->loc($modes, 1);
                $val1 = $this->ram[$loc1];
            }
            if ($jump > 2) {
                $loc2 = $this->loc($modes, 2);
                $val2 = $this->ram[$loc2];
            }
            if ($jump > 3) {
                $loc3 = $this->loc($modes, 3);
            }

            switch ($op) {
                case self::ADD:
                    $this->ram[$loc3] = $val1 + $val2;
                    break;
                case self::MUL:
                    $this->ram[$loc3] = $val1 * $val2;
                    break;
                case self::STO:
                    if (c($inputs) > $inputCount) {
                        $this->ram[$loc1] = $inputs[$inputCount];
                    } else {
                        return self::STO;
                    }
                    $inputCount++;
                    break;
                case self::OUT:
                    echo "$val1\n";
                    $this->outputs[] = $val1;
                    break;
                case self::JIT:
                    $newPos = ($val1 != 0 ? $val2 : $newPos);
                    break;
                case self::JIF:
                    $newPos = ($val1 == 0 ? $val2 : $newPos);
                    break;
                case self::LT:
                    $this->ram[$loc3] = ($val1 < $val2 ? 1 : 0);
                    break;
                case self::GT:
                    $this->ram[$loc3] = ($val1 > $val2 ? 1 : 0);
                    break;
                case self::EQ:
                    $this->ram[$loc3] = ($val1 == $val2 ? 1 : 0);
                    break;
                case self::NEQ:
                    $this->ram[$loc3] = ($val1 != $val2 ? 1 : 0);
                    break;
                case self::REL:
                    $this->rel += $val1;
                    break;
                default:
                    throw new Exception("Error encountered. Code: $op Jump: $jump Fop: $fop IP: {$this->ip} Rel: {$this->rel}");
            }
            $this->ip = ($newPos > -1) ? $newPos : $this->ip + $jump;
            $fop = $this->ram[$this->ip];
        }
        return self::STP;
    }
}
