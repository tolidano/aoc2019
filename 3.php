<?php
$data = trim(file_get_contents('3.input.txt'));
$wires = explode("\n", $data);
$wire1 = explode(',', trim($wires[0]));
$wire2 = explode(',', trim($wires[1]));

$bounds1 = findBounds($wire1);
var_dump($bounds1);
$bounds2 = findBounds($wire2);
var_dump($bounds2);

$numRows = $bounds1['rows'] > $bounds2['rows'] ? $bounds1['rows'] : $bounds2['rows'];
$numCols = $bounds1['cols'] > $bounds2['cols'] ? $bounds1['cols'] : $bounds2['cols'];

for ($i = 0; $i < $numRows + 5; $i++) {
    $rows[] = [];
}
$row = 5000;
$col = 5000;
$rows[$row][$col] = 9;
die;
paintWire($wire1, $rows, '1', $row, $col);
paintWire($wire2, $rows, '2', $row, $col);

function findBounds($wire) {
    $maxRow = -1000000;
    $minRow = 1000000;
    $maxCol = -1000000;
    $minCol = 1000000;
    $row = 0;
    $col = 0;
    foreach ($wire as $m) {
        $dir = substr($m, 0, 1);
        $len = (int)substr($m, 1);
        switch ($dir) {
            case 'U':
                $row -= $len;
                if ($row < $minRow) {
                    $minRow = $row;
                }
                break;
            case 'D':
                $row += $len;
                if ($row > $maxRow) {
                    $maxRow = $row;
                }
                break;
            case 'L':
                $col -= $len;
                if ($col < $minCol) {
                    $minCol = $col;
                }
                break;
            case 'R':
                $col += $len;
                if ($col > $maxCol) {
                    $maxCol = $col;
                }
                break;
        }
    }
    return ['rows' => $maxRow - $minRow, 'cols' => $maxCol - $minCol, 'offsetRow' => $minRow, 'offsetCol' => $minCol];
}

function paintWire($wire, &$rows, $paint, $row, $col) {
    foreach ($wire as $m) {
        $dir = substr($m, 0, 1);
        $len = (int)substr($m, 1);
        print("AN ITEM: $m $dir $len\n");
        switch ($dir) {
            case 'U':
                for ($q = 0; $q < $len; $q++) {
                    $row--;
                    if ($rows[$row][$col] != '.') {
                        $paint = '3';
                    }
                    if ($row < 0 || $col < 0) {
                        die('tried to exceed the bounds of the board going up');
                    }
                    $rows[$row][$col] = $paint;
                }
                break;
            case 'D':
                for ($q = 0; $q < $len; $q++) {
                    $row++;
                    if ($rows[$row][$col] != '.') {
                        $paint = 3;
                    }
                    if ($row < 0 || $col < 0) {
                        die('tried to exceed the bounds of the board going down');
                    }
                    $rows[$row][$col] = $paint;
                }
                break;
            case 'L':
                for ($q = 0; $q < $len; $q++) {
                    $col--;
                    if ($rows[$row][$col] != '.') {
                        $paint = 3;
                    }
                    if ($row < 0 || $col < 0) {
                        die('tried to exceed the bounds of the board going left');
                    }
                    $rows[$row][$col] = $paint;
                }
                break;
            case 'R':
                for ($q = 0; $q < $len; $q++) {
                    $col++;
                    if ($rows[$row][$col] != '.') {
                        $paint = 3;
                    }
                    if ($row < 0 || $col < 0) {
                        die('tried to exceed the bounds of the board going right');
                    }
                    $rows[$row][$col] = $paint;
                }
                break;
        } 
    }
}
