<?php
$input = '123257-647015';
$digits = 6;
$parts = explode('-', $input);
$min = (int)$parts[0];
$max = (int)$parts[1];
// 2 adjacent digits the same
// same or increasing digits
$count = 0;
for ($i = $min; $i < $max; $i++) {
    $doubles = checkDoubles((string)$i);
    $increase = checkDigits((string)$i);
    if ($doubles && $increase) {
        echo "GOOD: $i\n";
        $count++;
    }
}
echo "$count\n";

function checkDoubles($i) {
    if (
        substr($i, 0, 1) == substr($i, 1, 1) ||
        substr($i, 1, 1) == substr($i, 2, 1) ||
        substr($i, 2, 1) == substr($i, 3, 1) ||
        substr($i, 3, 1) == substr($i, 4, 1) ||
        substr($i, 4, 1) == substr($i, 5, 1)
    ) {
        return true;
    }
    return false;
}

function checkDigits($i) {
    $min = 0;
    for ($j = 0; $j < strlen($i); $j++) {
        if ((int)substr($i, $j, 1) < $min) {
            return false;
        }
        $min = (int)substr($i, $j, 1);
    }
    return true;
}
