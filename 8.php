<?php
$data = file_get_contents('8.input.txt');
$width = 25;
$height = 6;
$done = false;
$layers = [];
while (!$done) {
    $layer = substr($data, 0, $width * $height);
    $data = substr($data, $width * $height);
    $layers[] = $layer;
    if (strlen($data) < $width * $height) {
        $done = true;
    }
}
$final = end($layers);
for ($i = count($layers) - 2; $i >= 0; $i--) {
    $layer = $layers[$i];
    for ($j = 0; $j < $width * $height; $j++) {
        if (substr($layer, $j, 1) != 2) {
            $pixel = substr($layer, $j, 1);
            if (substr($final, $j, 1) != substr($layer, $j, 1)) {
                if ($j == 0) {   
                    $final = substr($layer, $j, 1) . substr($final, 1);
                } elseif ($j == $width * $height -1) {
                    $final = substr($final, 0, -1) . substr($layer, $j, 1);
                } else {
                    $final = substr($final, 0, $j) . substr($layer, $j, 1) . substr($final, $j + 1);
                }
            }
        }
    }
}
printLayer($final);

function printLayer($layer) {
    for ($m = 0; $m < 6; $m++) {
        echo str_replace(['2', '0'], ' ', substr($layer, $m * 25, 25)) . "\n";
    }
}
