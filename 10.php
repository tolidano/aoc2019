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

$li = li('10.input.txt');
$asteroids = [];
$lineLength = 0;
$row = 0;
foreach ($li as $line) {
    $lineLength = l($line);
    $asteroids[$row] = [];
    for ($col = 0; $col <= l($line); $col++) {
        $asteroids[$row][$col] = s($line, $col, 1);
    }
    $row++;
}

printAsteroids($asteroids);

$maxVisible = 0;
$maxVR = 0;
$maxVC = 0;
foreach ($asteroids as $r => $row) {
    foreach ($row as $c => $col) {
        $visible = calcVisible($r, $c, $asteroids, $lineLength);
        if (c($visible) > $maxVisible) {
            $maxVisible = c($visible);
            $maxVR = $r;
            $maxVC = $c;
        }
    }
}

echo "Max Visible: $maxVisible at Row: $maxVR and Col: $maxVC\n";

echo find200thAsteroid($asteroids, $maxVR, $maxVC);

function find200thAsteroid($asteroids, $maxVR, $maxVC) {
    // points up, rotates clockwise, switch # to ., keep count
    $vaporized = 0;
    while ($vaporized != 1) {
        $visible = calcVisible($maxVR, $maxVC, $asteroids, c($asteroids[0]));
        foreach ($visible as $arr) {
            $asteroids[$arr['row']][$arr['col']] = '.'; //vaporize
            $vaporized++;
            if ($vaporized >= 199 && $vaporized <= 201) {
                echo "JUST VAPORIZED $vaporized ASTEROIDS {$arr['row']} {$arr['col']}\n";
            }
        }
    }
}

function printAsteroids($asteroids, $lineLength = null) {
    if (!$lineLength) {
        $lineLength = c($asteroids[0]);
    }
    for ($r = 0; $r < c($asteroids); $r++) {
        for ($c = 0; $c < $lineLength; $c++) {
            echo $asteroids[$r][$c];
        }
        echo "\n";
    }
}

function calcVisible($row, $col, $asteroids, $lineLength) {
    $visible = [];
    $numAsteroids = 0;
    $debug = false;
    if ($col == -5 && $row == 8) {
        $debug = true;
    }
    if ($asteroids[$row][$col] != '#') {
        return [];
    }
    for ($r = 0; $r < c($asteroids); $r++) {
        for ($c = 0; $c < $lineLength; $c++) {
            if ($asteroids[$r][$c] == '#' && ($r != $row || $c != $col)) {
                $numAsteroids++;
                $diffR = $row - $r;
                $diffC = $col - $c;
                if ($diffC != 0) {
                    if ($diffR != 0) {
                        $slope = $diffR / $diffC;
                        if ($diffR > 0 && $diffC > 0) {
                            $slope = "UR$slope";
                        } elseif ($diffR < 0 && $diffC < 0) {
                            $slope = "DL$slope";
                        } elseif ($diffR < 0 && $diffC > 0) {
                            $slope = "UL$slope";
                        } else {
                            $slope = "DR$slope";
                        }
                    } elseif ($diffC > 0) {
                        $slope = "PHINF";
                    } else {
                        $slope = "NHINF";
                    }
                } elseif ($diffR > 0) {
                    $slope = "NVINF";
                } else {
                    $slope = "PVINF";
                }
                $slope = "X$slope";
                $bit = "";
                if (isset($visible[$slope])) {
                    $bit = "SEEN";
                }
                $cur = c($visible);
                if ($debug) {
                    echo "$bit FOR ($col, $row) and ($c, $r) calc (total: $cur) slope of $slope ($diffR) / ($diffC)\n";
                }
                $dist = sqrt(($diffC * $diffC) + ($diffR * $diffR));
                if (!isset($visible[$slope]) || $visible[$slope]['dist'] > $dist) {
                    $visible[$slope] = ['col' => $c, 'row' => $r, 'dist' => $dist];
                }
            }
        }
    }
    return $visible;
}
