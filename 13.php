<?php
ini_set('memory_limit','6G');

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

$c = new Computer;
$c->res('13.input.txt');
$c->run();
$x = 0;
$blocks = 0;
$game = [];
foreach ($c->outputs as $out) {
    if ($x == 0) {
        $left = $out;
    } elseif ($x == 1) {
        $top = $out;
    } elseif ($x == 2) {
        $tile = $out;
        if ($tile == 2) {
            $blocks++;
        }
    }
    $game[$top][$left] = $tile;
    $x = ($x + 1) % 3;
}
var_dump($game);
echo "BLOCKS: $blocks\n";
