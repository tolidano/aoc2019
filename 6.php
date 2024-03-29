<?php
$data = file_get_contents('6.input.txt');
$lines = explode("\n", $data);
$orbits = [];
$root = 'COM';
$you = 'YOU';
$santa = 'SAN';
foreach ($lines as $line) {
    if (strpos($line, ')')) {
        $parts = explode(')', $line);
        $center = $parts[0];
        $orbit = $parts[1];
        if (!isset($orbits[$center])) {
            $orbits[$center] = new Node;
            $orbits[$center]->value = $center;
            $orbits[$center]->children = [];
        }
        if (!isset($orbits[$orbit])) {
            $orbits[$orbit] = new Node;
            $orbits[$orbit]->value = $orbit;
            $orbits[$orbit]->children = [];
        }
        $orbits[$center]->children[] = $orbits[$orbit];
        $orbits[$orbit]->parent = $orbits[$center];
    }
}

function countLinks($node, $depth = 0) {
    $numOrbits = $depth;
    if ($node->children) {
        foreach ($node->children as $child) {
            $numOrbits += countLinks($child, $depth + 1);
        }
    }
    return $numOrbits;
}

function displayPathToRoot($node, $check = '') {
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
    $path = substr($path, 0, strlen($path) - 2);
    return $path;
}

$path = displayPathToRoot($orbits[$you]);
$path2 = displayPathToRoot($orbits[$santa], $path);


class Node {
    public $value;
    public $parent;
    public $children;
}
