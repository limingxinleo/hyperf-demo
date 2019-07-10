<?php
include dirname(__DIR__).'/bootstrap.php';

$s = '11, 33, 22, 44,12,32,55,23,19,23';

$data = _string($s)
    ->split(',')
    ->each(function (&$item){ $item = intval($item);})
    ->sort()
    ->unique()
    ->toArray();

var_dump($data);
