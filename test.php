<?php

$num = 2345;

if ( is_int($num) ) 
    echo "Is int" . PHP_EOL;
else
    echo "Is NOT int" . PHP_EOL;


if ( empty($num) ) 
    echo "Is Empty" . PHP_EOL;
else 
    echo "Is NOT Empty" . PHP_EOL;


if ( is_null($num) )
    echo "Is Null" . PHP_EOL;
else    
    echo "Is NOT Null" . PHP_EOL;
