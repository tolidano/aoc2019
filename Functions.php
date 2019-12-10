<?php
function s($a, $b, $c = null) {
    if ($c) return substr($a, $b, $c);
    else return substr($a, $b);
}

function p($hs, $n, $st = 0) {
    return strpos($hs, $n, $st);
}

function l($str) {
    return strlen($str);
}

function t($str) {
    return trim($str);
}

function cc($str) {
    return count_chars($str, 1);
}

function fgc($f) {
    return file_get_contents($f);
}

function c($arr) {
    return count($arr);
}

function ex($del, $str) {
    return explode($del, $str);
}

function im($del, $arr) {
    return implode($del, $arr);
}

function ch($str, $size) {
    while ($str != '') {
        $o[] = s($str, 0, $size - 1);
        $str = s($str, $size);
    }
}

function li($f, $del = "\n") {
    $data = ex($del, fgc($f));
    if (!trim(end($data))) {
        unset($data[c($data) - 1]);
    }
    return $data;
}

function pr($str) {
    echo "$str\n";
}

function lp($wire) {
    // trace a path a wire takes in 2d
    $list = [];
    $row = 0;
    $col = 0;
    $steps = 0;
    foreach ($wire as $m) {
        $dir = s($m, 0, 1);
        $len = (int)s($m, 1);
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

function x($list1, $list2) {
    // given two lists of coordinates, do any cross, min dist, min sum
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

    echo "MIN DIST: $minDist\nMIN SUM: $minSum\n";
}

function checkLargeGroup($i) {
    // not part of a larger group (3+)
    if (
        (s($i, 0, 1) == s($i, 1, 1) && s($i, 1, 1) != s($i, 2, 1)) ||
        (s($i, 1, 1) == s($i, 2, 1) && s($i, 2, 1) != s($i, 3, 1) && s($i, 1, 1) != s($i, 0, 1)) ||
        (s($i, 2, 1) == s($i, 3, 1) && s($i, 3, 1) != s($i, 4, 1) && s($i, 2, 1) != s($i, 1, 1)) ||
        (s($i, 3, 1) == s($i, 4, 1) && s($i, 4, 1) != s($i, 5, 1) && s($i, 3, 1) != s($i, 2, 1)) ||
        (s($i, 4, 1) == s($i, 5, 1) && s($i, 5, 1) != s($i, 3, 1))
    ) {
        return true;
    }
    return false;
}

function checkDoubles($i) {
    // pairs of digits
    if (
        s($i, 0, 1) == s($i, 1, 1) ||
        s($i, 1, 1) == s($i, 2, 1) ||
        s($i, 2, 1) == s($i, 3, 1) ||
        s($i, 3, 1) == s($i, 4, 1) ||
        s($i, 4, 1) == s($i, 5, 1)
    ) {
        return true;
    }
    return false;
}

function checkDigits($i) {
    // strctly increasing digits
    $min = 0;
    for ($j = 0; $j < strlen($i); $j++) {
        if ((int)s($i, $j, 1) < $min) {
            return false;
        }
        $min = (int)s($i, $j, 1);
    }
    return true;
}

function countLinks($node, $depth = 0) {
    // how many edges along this branch
    $numOrbits = $depth;
    if ($node->children) {
        foreach ($node->children as $child) {
            $numOrbits += countLinks($child, $depth + 1);
        }
    }
    return $numOrbits;
}

function displayPathToRoot($node, $check = '') {
    // display path to root, if check set to another path, find common parent
    $path = "{$node->value}->";
    $length = 0;
    while ($node->value != 'COM') {
        $parent = $node->parent;
        $path .= "{$parent->value}->";
        if ($check && strpos($check, $parent->value)) {
            $parts = explode('>', $check);
            $you = 0;
            while (str_replace('-', '', $parts[$you]) != $parent->value) {
                $you++;
            }
            $length--;
            echo "Common parent to you: $you\n";
            echo "Common parent to Santa: $length\n";
            $sum = $length + $you;
            echo "$sum\n";
            return $path;
        }
        $length++;
        $node = $parent;
    }
    $path = s($path, 0, strlen($path) - 2);
    return $path;
}
