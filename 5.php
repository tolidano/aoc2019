<?php
const STOP = 99;
const ADD = 1;
const MUL = 2;
const STORE = 3;
const OUTPUT = 4;

function restore() {
    $dump = trim(file_get_contents('5.input.txt'));
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
    while ($fop != STOP) {
        $digits = (string)$fop;
        if (strlen($digits) < 3) {
            $op = (int)$fop;
            $modes = '';
        } else {
            $op = substr($digits, -2);
            $modes = substr($digits, 0, strlen($digits) - 2);
        }
        switch ($op) {
            case ADD:
                if ($modes && substr($modes, -1, 1) == '1') {
                    $val1 = $ram[$pos + 1];
                    $i1l = -1;
                } else {
                    $i1l = $ram[$pos + 1];
                    $val1 = $ram[$i1l];
                }
                if (strlen($modes) > 1 && substr($modes, -2, 1) == '1') {
                    $val2 = $ram[$pos + 2];
                    $i2l = -1;
                } else {
                    $i2l = $ram[$pos + 2];
                    $val2 = $ram[$i2l];
                }
                $o1l = $ram[$pos + 3];
                $jump = 4;
                $ram[$o1l] = $val1 + $val2;
                break;
            case MUL:
                if ($modes && substr($modes, -1, 1) == '1') {
                    $val1 = $ram[$pos + 1];
                } else {
                    $i1l = $ram[$pos + 1];
                    $val1 = $ram[$i1l];
                }
                if (strlen($modes) > 1 && substr($modes, -2, 1) == '1') {
                    $val2 = $ram[$pos + 2];
                } else {
                    $i2l = $ram[$pos + 2];
                    $val2 = $ram[$i2l];
                }
                $o1l = $ram[$pos + 3];
                $jump = 4;
                $ram[$o1l] = $val1 * $val2;
                break;
            case STORE:
                $o1l = $ram[$pos +1];
                $jump = 2;
                $ram[$o1l] = $inputs[$inputCount];
                $inputCount++;
                break;
            case OUTPUT:
                if ($modes && substr($modes, -1, 1) == '1') {
                    $o1l = -1;
                    $val = $ram[$pos + 1];
                } else {
                    $o1l = $ram[$pos + 1];
                    $val = $ram[$o1l];
                }
                $jump = 2;
                echo "$val\n";
                break;
            default:
                $err = true;
                echo "ERROR\n";
                $op = STOP;
        }
        $pos += $jump;
        $fop = $ram[$pos];
    }
    return $ram;
}

$ram = restore();
$ramOut = computer($ram, 0, [1]);
