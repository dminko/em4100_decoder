<?php

error_reporting(E_ALL & ~E_NOTICE);

// Със STR_PAD_LEFT
//
//[1111111110100100011000000000001001011111010001010001011000100110] => 0000000001000111,1010010100101000
//																					  71,           42280           <-------------
//																								  4695336           <-------------
//[1111111110100100011000000000001001011111010001010000001110101110] => 0000000001000111,1010010100001110
//                                                                                    71,           42254           <-------------
//																								  4695310           <-------------
//[1111111110011000000000000000010100100101001010100001011011110100] => 0000000010101001,1001101000101011
//																					 169,           39467           <-------------
//																								 11115051           <-------------




$rfid[] = "26 16 45 5F 02 60 A4 FF"; //  4695336 или 071,42280
$rfid[] = "AE 03 45 5F 02 60 A4 FF"; //  4695310 или 071,42254
$rfid[] = "F4 16 2A 25 05 00 98 FF"; // 11115051 или 169,39467

$resI = array();

/***
/* return array
***/
function reverseOrder($input) 
{
    $i = $len = strlen($input);
    $res = array();
    $ri = 0;
    
    while (1 <= $i) {
        $i--;
        $ch = substr($input, $i, 1);
        if (trim($ch) <> '') {
            $res[$ri] = $ch . $res[$ri];
            if (strlen($res[$ri]) == 2) {
                $ri++;
            }
        }
    }
    
    return $res;
}
foreach ($rfid as $rf) {
    $resI[] = reverseOrder($rf);
}

$bit64 = '';
foreach ($resI as $res) {
    $bit64 = '';
    foreach ($res as $byte) {
        $bit64 .= str_pad(decbin(hexdec($byte)), 8, '0', STR_PAD_LEFT);
    }
    $bit64Arr[] = $bit64;
}

$dataBits = array(	20,21,22,23, 
                    25,26,27,28,
                    30,31,32,33,
                    35,36,37,38,
                    40,41,42,43,
                    45,46,47,48,
                    50,51,52,53,
                    55,56,57,58
                );

$data = array();

foreach ($bit64Arr as $binRes) {
    foreach ($dataBits as $bit) {
        $data[$binRes] .= $binRes{$bit-1};
    }
}

print_r ($data);

exit(0);

