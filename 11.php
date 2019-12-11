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

$c = new Computer();
$c->res('11.input.txt');

$painted = 0;
$done = false;
$x = 0;
$y = 0;
$xc = "C$x";
$yc = "C$y";
$panels[$xc][$yc] = 1;
$faces = ['U', 'L', 'D', 'R'];
$face = 0;
while (!$done) {
    $xc = "C$x";
    $yc = "C$y";
    $in = 0;
    if (isset($panels[$xc][$yc])) {
        $in = $panels[$xc][$yc];
    } else {
        $painted++;
    }
    $exit = $c->run([$in]);
    if ($exit == Computer::STP) {
        $done = true;
    }
    $oc = c($c->outputs);
    $paint = $c->outputs[$oc - 2];
    $move = $c->outputs[$oc - 1];
    $panels[$xc][$yc] = $paint;
    if ($move == 0) {
        $face += 1;
        if ($face == 4) {
            $face = 0;
        }
    } else {
        $face -= 1;
        if ($face == -1) {
            $face = 3;
        }
    }
    switch ($face) {
        case 0:
            $y = $y - 1;
        break;
        case 1:
            $x = $x - 1;
        break;
        case 2:
            $y = $y + 1;
        break;
        case 3:
            $x = $x + 1;
        break;
    }
}
echo "PAINTED: $painted\n";
var_dump($panels);
foreach ($panels as $row) {
    for ($i = 0; $i < 6; $i++) {
        $out = '.';
        if (isset($row["C$i"]) && $row["C$i"] == 1) {
            $out = '#';
        }
        echo $out;
    }
    echo "\n";
}
