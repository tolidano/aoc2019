<?php
function main() : void {
    $v = file_get_contents('1.input.txt');
    $l = explode("\n", $v);
    $s = 0;
    foreach ($l as $m) {
        if ($m) {
            $s += calcFuel($m);
        }
    }
    print($s);
}

function calcFuel(int $mass): int {
    $f = floor($mass / 3) - 2;
    if ($f > 8) {
        $f += calcFuel($f);
    }
    return $f;
}

main();
