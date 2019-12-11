<?php 
require_once 'config.php';
require_once 'em4100Decode.php';

$fp = fopen(EXIT_PORT, 'r');
$rfid = '';
while (true) {
     
    $ch = fread($fp, 1);
    if ($ch != '?') {
        $rfid .= $ch;
    } else {
        echo (decode($rfid) . "\n");
        $rfid = '';
    }
}