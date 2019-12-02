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
    return floor($mass / 3) - 2;
}

main();
