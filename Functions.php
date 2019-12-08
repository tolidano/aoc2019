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
    return ex($del, fgc($f));
}
