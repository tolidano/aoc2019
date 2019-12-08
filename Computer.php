<?php
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
    const GT = 9;
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
    ];

    public $ram = [];
    public $outputs = [];
    public $inputs = [];
    public $ip = 0;

    public function restore($file) {
        $dump = trim(file_get_contents($file));
        $ram = explode(',', $dump);
        foreach ($ram as &$r) {
            $r = (int)$r;
        }
        $this->ram = $ram;
    }

    public function run($inputs = []) {
        $this->inputs = $inputs;
        $inputCount = 0;
        $ip = $this->ip;
        $ram = $this->ram;
        $fop = $ram[$ip];
        while ($fop != self::STP) {
            $digits = (string)$fop;
            if (strlen($digits) < 3) {
                $op = (int)$fop;
                $modes = '';
            } else {
                $op = (int)substr($digits, -2);
                $modes = substr($digits, 0, strlen($digits) - 2);
            }
            $newPos = -1;
            $jump = self::SIZES[$op];
            if ($jump > 1) {
                $val1 = (strlen($modes) > 0 && substr($modes, -1, 1) == '1') ? $ram[$ip + 1] : $ram[$ram[$ip + 1]];
            }
            if ($jump > 2) {
                $val2 = (strlen($modes) > 1 && substr($modes, -2, 1) == '1') ? $ram[$ip + 2] : $ram[$ram[$ip + 2]];
            }
            
            switch ($op) {
                case self::ADD:
                    $o1l = $ram[$ip + 3];
                    $ram[$o1l] = $val1 + $val2;
                    break;
                case self::MUL:
                    $o1l = $ram[$ip + 3];
                    $ram[$o1l] = $val1 * $val2;
                    break;
                case self::STO:
                    $o1l = $ram[$ip +1];
                    if (count($inputs) > $inputCount) {
                        $ram[$o1l] = $inputs[$inputCount];
                    } else {
                        $this->ram = $ram;
                        return self::STO;  
                    }
                    $inputCount++;
                    break;
                case self::OUT:
                    echo "OUT: $val1\n";
                    $this->outputs[] = $val1;
                    $out = $val1;
                    break;
                case self::JIT:
                    $newPos = ($val1 != 0 ? $val2 : $newPos);
                    break;
                case self::JIF:
                    $newPos = ($val1 == 0 ? $val2 : $newPos);
                    break;
                case self::LT:
                    $o1l = $ram[$ip + 3];
                    $ram[$o1l] = ($val1 < $val2 ? 1 : 0);
                    break;
                case self::GT:
                    $o1l = $ram[$ip + 3];
                    $ram[$o1l] = ($val1 > $val2 ? 1 : 0);
                    break;
                case self::EQ:
                    $o1l = $ram[$ip + 3];
                    $ram[$o1l] = ($val1 == $val2 ? 1 : 0);
                    break;
                case self::NEQ:
                    $o1l = $ram[$ip + 3];
                    $ram[$o1l] = ($val1 != $val2 ? 1 : 0);
                    break;
                default:
                    throw new Exception("Error encountered.");
            }
            $ip = ($newPos > -1) ? $newPos : $ip + $jump;
            $this->ip = $ip;
            $fop = $ram[$ip];
        }
        $this->ram = $ram;
        return self::STP;
    }
}
