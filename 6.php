<?php
$data = file_get_contents('6.input.txt');
$lines = explode("\n", $data);
$orbits = [];
$root = 'COM';
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

echo countLinks($orbits[$root]);

class Node {
    public $value;
    public $parent;
    public $children;
}
