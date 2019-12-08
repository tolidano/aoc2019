<?php
$data = file_get_contents('8.input.txt');
$width = 25;
$height = 6;
$done = false;
$minLayer = [];
$minZero = 100000;
while (!$done) {
    $layer = substr($data, 0, $width * $height);
    $data = substr($data, $width * $height);
    $cc = count_chars($layer, 1);
    if ($cc[48] < $minZero) {
        $minZero = $cc[48];
        $minLayer = $cc;
    }
    if (strlen($data) < $width * $height) {
        $done = true;
    }
}
echo $minLayer[49] * $minLayer[50];
