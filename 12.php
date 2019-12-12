<?php
include_once('Computer.php');
include_once('Functions.php');
include_once('Node.php');
include_once('Tree.php');

// l = strlen
// p = strpos
// s = substr
// cc = count_chars
// ex = explode
// im = implode
// c = count
// li = lines
// ch = chunks
// t = trim

$li = li('12.input.txt');
$i = ex('=', $li[0]);
$m[0]['x'] = s($i[1], 0, p($i[1], ','));
$m[0]['y'] = s($i[2], 0, p($i[2], ','));
$m[0]['z'] = s($i[3], 0, p($i[3], '>'));
$m[0]['xv'] = 0;
$m[0]['yv'] = 0;
$m[0]['zv'] = 0;
$i = ex('=', $li[1]);
$m[1]['x'] = s($i[1], 0, p($i[1], ','));
$m[1]['y'] = s($i[2], 0, p($i[2], ','));
$m[1]['z'] = s($i[3], 0, p($i[3], '>'));
$m[1]['xv'] = 0;
$m[1]['yv'] = 0;
$m[1]['zv'] = 0;
$i = ex('=', $li[2]);
$m[2]['x'] = s($i[1], 0, p($i[1], ','));
$m[2]['y'] = s($i[2], 0, p($i[2], ','));
$m[2]['z'] = s($i[3], 0, p($i[3], '>'));
$m[2]['xv'] = 0;
$m[2]['yv'] = 0;
$m[2]['zv'] = 0;
$i = ex('=', $li[3]);
$m[3]['x'] = s($i[1], 0, p($i[1], ','));
$m[3]['y'] = s($i[2], 0, p($i[2], ','));
$m[3]['z'] = s($i[3], 0, p($i[3], '>'));
$m[3]['xv'] = 0;
$m[3]['yv'] = 0;
$m[3]['zv'] = 0;

$history = [];
$done = false;

$t = 0;
$coord = ['x', 'y', 'z'];

while (!$done) {
    // update velocity with gravity
    for ($i = 0; $i < c($m); $i++) {
        for ($j = 0; $j < c($m); $j++) {
            if ($i != $j) {
                foreach ($coord as $co) {
                    if ($m[$i][$co] < $m[$j][$co]) {
                        $m[$i]["{$co}v"] += 1;
                    } elseif ($m[$i][$co] > $m[$j][$co]) {
                        $m[$i]["{$co}v"] -= 1;
                    }
                }
            }
        }
    }
    // update position with velocity
    for ($i = 0; $i < c($m); $i++) {
        foreach ($coord as $co) {
            $m[$i][$co] += $m[$i]["{$co}v"];
        }
    }
    $t++;
    /*
    if ($t > 998) {
        $total = 0;
        for ($i = 0; $i < c($m); $i++) {
            $pot = 0;
            $kin = 0;
            foreach ($coord as $co) {
                $pot += abs($m[$i][$co]);
                $kin += abs($m[$i]["{$co}v"]);
            }
            $total += $pot * $kin;
        }
        echo "TOTAL KINETIC: $total\n";
    }
    */
}
