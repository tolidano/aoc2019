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

function computer($ram, $start = 0) {
    $pos = $start;
    $fop = $ram[$pos];
    $err = false;
    while ($fop != STOP) {
        $digits = (string)$fop;
        echo "-----------\nfop: $fop digits: $digits\n";
        if (strlen($digits) < 3) {
            $op = (int)$fop;
            $modes = '';
            echo "modes: $modes op: $op\n";
        } else {
            $op = substr($digits, -2);
            $modes = substr($digits, 0, strlen($digits) - 2);
            echo "modes: $modes op: $op\n";
            // modes: last char = param 1, default 0, pos mode (1 = imm mode)
        }
        switch ($op) {
            case ADD:
                echo "ADD modes: $modes x1: {$ram[$pos + 1]} x2: {$ram[$pos + 2]} y1: {$ram[$pos + 3]}\n";
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
                echo "VAL1: $val1 VAL2: $val2 O1L: $o1l\n";
                $ram[$o1l] = $val1 + $val2;
                break;
            case MUL:
                echo "MUL modes: $modes x1: {$ram[$pos + 1]} x2: {$ram[$pos + 2]} y1: {$ram[$pos + 3]}\n";
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
                echo "VAL1: $val1 VAL2: $val2 O1L: $o1l\n";
                $ram[$o1l] = $val1 * $val2;
                break;
            case STORE:
                echo "STORE modes: $modes y1: {$ram[$pos + 1]}\n";
                $o1l = $ram[$pos +1];
                $jump = 2;
                $ram[$o1l] = '1';
                echo "ASSUMING INPUT OF 1\n";
                break;
            case OUTPUT:
                echo "OUTPUT modes: $modes y1: {$ram[$pos + 1]}\n";
                $o1l = $ram[$pos +1];
                $jump = 2;
                echo "OUTPUT: {$ram[$o1l]}\n";
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
$ramOut = computer($ram);
