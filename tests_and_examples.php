<?php
require_once (__DIR__ . '/core/AppAutoloader.php');


$var = 1;

$casted = (bool)$var;

$res = $casted === TRUE;

var_dump($res);

exit;





