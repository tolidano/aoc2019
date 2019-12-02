<?php
const STOP = 99;
const ADD = 1;
const MUL = 2;
const JUMP = 4;

function restore() {
    $dump = trim(file_get_contents('2.input.txt'));
    $ram = explode(',', $dump);
    foreach ($ram as &$r) {
        $r = (int)$r;
    }
    return $ram;
}

function computer($ram, $start = 0) {
    $pos = $start;
    $op = $ram[$pos];
    $err = false;
    while ($op != STOP) {
        $i1l = $ram[$pos + 1];
        $i2l = $ram[$pos + 2];
        $o1l = $ram[$pos + 3];
        switch ($op) {
            case ADD:
                $ram[$o1l] = $ram[$i1l] + $ram[$i2l];
                break;
            case MUL:
                $ram[$o1l] = $ram[$i1l] * $ram[$i2l];
                break;
            default:
                $err = true;
                $op = STOP;
        }
        $pos += JUMP;
        $op = $ram[$pos];
    }
    return $ram;
}

$ram = restore();
for ($i = 0; $i < 99; $i++) {
    for ($j = 0; $j < 99; $j++) {
        $ramTry = $ram;
        $ramTry[1] = $i;
        $ramTry[2] = $j;
        $ramOut = computer($ramTry);
        if ($ramOut[0] == 19690720) {
            print(100 * $i + $j);
            exit();
        }
    }
}
