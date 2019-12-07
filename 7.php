<?php
const STOP = 99;
const ADD = 1;
const MUL = 2;
const STORE = 3;
const OUTPUT = 4;
const JIT = 5;
const JIF = 6;
const LT = 7;
const EQ = 8;
const GT = 9;
const NEQ = 10;

const SIZES = [
    STOP => 1,
    ADD => 4,
    MUL => 4,
    STORE => 2,
    OUTPUT => 2,
    JIT => 3,
    JIF => 3,
    LT => 4,
    EQ => 4,
    GT => 4,
    NEQ => 4,
];

function restore() {
    $dump = trim(file_get_contents('7.input.txt'));
    $ram = explode(',', $dump);
    foreach ($ram as &$r) {
        $r = (int)$r;
    }
    return $ram;
}

function computer($ram, $start = 0, $inputs = []) {
    $inputCount = 0;
    $pos = $start;
    $fop = $ram[$pos];
    $err = false;
    $out = 0;
    while ($fop != STOP) {
        $digits = (string)$fop;
        if (strlen($digits) < 3) {
            $op = (int)$fop;
            $modes = '';
        } else {
            $op = (int)substr($digits, -2);
            $modes = substr($digits, 0, strlen($digits) - 2);
        }
        $newPos = -1;
        $jump = SIZES[$op];
        if ($jump > 1) {
            $val1 = (strlen($modes) > 0 && substr($modes, -1, 1) == '1') ? $ram[$pos + 1] : $ram[$ram[$pos + 1]];
        }
        if ($jump > 2) {
            $val2 = (strlen($modes) > 1 && substr($modes, -2, 1) == '1') ? $ram[$pos + 2] : $ram[$ram[$pos + 2]];
        }
        
        switch ($op) {
            case ADD:
                $o1l = $ram[$pos + 3];
                $ram[$o1l] = $val1 + $val2;
                break;
            case MUL:
                $o1l = $ram[$pos + 3];
                $ram[$o1l] = $val1 * $val2;
                break;
            case STORE:
                $o1l = $ram[$pos +1];
                $in = $out;
                if (isset($inputs[$inputCount]) && $inputs[$inputCount] != 'o') {
                    $in = $inputs[$inputCount];
                }
                echo "IN: $in\n";
                $ram[$o1l] = $in;
                $inputCount++;
                break;
            case OUTPUT:
                echo "OUT: $val1\n";
                $outputs[] = $val1;
                $out = $val1;
                break;
            case JIT:
                $newPos = ($val1 != 0 ? $val2 : $newPos);
                break;
            case JIF:
                $newPos = ($val1 == 0 ? $val2 : $newPos);
                break;
            case LT:
                $o1l = $ram[$pos + 3];
                $ram[$o1l] = ($val1 < $val2 ? 1 : 0);
                break;
            case GT:
                $o1l = $ram[$pos + 3];
                $ram[$o1l] = ($val1 > $val2 ? 1 : 0);
                break;
            case EQ:
                $o1l = $ram[$pos + 3];
                $ram[$o1l] = ($val1 == $val2 ? 1 : 0);
                break;
            case NEQ:
                $o1l = $ram[$pos + 3];
                $ram[$o1l] = ($val1 != $val2 ? 1 : 0);
                break;
            default:
                $err = true;
                echo "ERROR\n";
                $op = STOP;
        }
        $pos = ($newPos > -1) ? $newPos : $pos + $jump;
        $fop = $ram[$pos];
    }
    return ['RAM' => $ram, 'OUTPUTS' => $outputs];
}

$ram = restore();
$m = [0, 1, 2, 3, 4]; // 7
$n = [5, 6, 7, 8, 9]; // 7.2
$try = [];
while (count($try) < 120) {
    shuffle($n);
    $str = implode('-', $n);
    if (!isset($try[$str])) {
        $try[$str] = 1;
    }
}
$maxOut = 0;
foreach ($try as $one => $ignore) {
    echo "TRY: $one\n";
    $input = explode('-', $one);
    $input[9] = 'o';
    $input[8] = $input[4];
    $input[7] = 'o';
    $input[6] = $input[3];
    $input[5] = 'o';
    $input[4] = $input[2];
    $input[3] = 'o';
    $input[2] = $input[1];
    $input[1] = 0;
    $ran = computer($ram, 0, $input);
    $last = end($ran['OUTPUTS']);
    if ($last > $maxOut) {
        $maxOut = $last;
    }
}
echo "MAX OUTPUT: $maxOut\n";
