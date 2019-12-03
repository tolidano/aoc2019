<?php
$data = trim(file_get_contents('3.input.txt'));
$wires = explode("\n", $data);
$wire1 = explode(',', trim($wires[0]));
$wire2 = explode(',', trim($wires[1]));

$list1 = listPoints($wire1);
$list2 = listPoints($wire2);

$minDist = 1000000;
$minSum = 1000000;

foreach ($list1 as $k => $v) {
    if (isset($list2[$k])) {
        echo "Lines Cross at $k\n";
        $sum = $v + $list2[$k];
        if ($sum < $minSum) {
            $minSum = $sum;
        }
        $parts = explode('|', $k);
        $rows = (int)$parts[1];
        $cols = (int)$parts[2];
        $dist = abs($rows) + abs($cols);
        if ($dist < $minDist) {
            $minDist = $dist;
        }
    }
}

echo "MIN DIST: $minDist\n";
echo "MIN SUM: $minSum\n";

function listPoints($wire) {
    $list = [];
    $row = 0;
    $col = 0;
    $steps = 0;
    foreach ($wire as $m) {
        $dir = substr($m, 0, 1);
        $len = (int)substr($m, 1);
        switch ($dir) {
            case 'U':
                for ($q = 0; $q < $len; $q++) {
                    $row--;
                    $steps++;
                    $list["RC|{$row}|{$col}"] = $steps;
                }
                break;
            case 'D':
                for ($q = 0; $q < $len; $q++) {
                    $row++;
                    $steps++;
                    $list["RC|{$row}|{$col}"] = $steps;
                }
                break;
            case 'L':
                for ($q = 0; $q < $len; $q++) {
                    $col--;
                    $steps++;
                    $list["RC|{$row}|{$col}"] = $steps;
                }
                break;
            case 'R':
                for ($q = 0; $q < $len; $q++) {
                    $col++;
                    $steps++;
                    $list["RC|{$row}|{$col}"] = $steps;
                }
                break;
        } 
    }
    return $list;
}
