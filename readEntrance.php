<?php 
require_once 'config.php';
require_once 'em4100Decode.php';
// вход :'1001'
// изход:'1002'

// $stamp = YYYYMMDDHHMMSS
$fp = fopen(ENTRANCE_PORT, 'r');
$rfid = '';
while (true) {
     
    $ch = fread($fp, 1);
    if ($ch != '?') {
        $rfid .= $ch;
    } else {
        $card = decode($rfid);
        // Изпращаме картата
        $search = array('{card}','{stamp}');
        $stamp = date("YmdHis");
        $replace = array($card, $stamp);
        
        $url = str_replace($search,$replace,ENTRANCE_URL);
        
        $res = @file_get_contents($url);
        
        if (FALSE === $res) {
            // Неработещо УРЛ
            file_put_contents('debug.log', date("Y-m-d H:i:s") . " " . basename(__FILE__). " " . " - Неработещо УРЛ \n", FILE_APPEND);
            file_put_contents('noSended.log', "entrance|{$card}|{$stamp}|ConnectionError\n", FILE_APPEND);
            continue;
        }
        if (strpos($res,'inserted')!==FALSE) {
            // Въведен запис в BGERpa
            file_put_contents('debug.log', date("Y-m-d H:i:s") . " " . basename(__FILE__). " " ." - $card - $stamp - Въведен запис в BGERpa \n", FILE_APPEND);
            
        } else {
                // Warning
            file_put_contents('debug.log', date("Y-m-d H:i:s") .  " " . basename(__FILE__). " ". $url ." - $card - $stamp - Неполучено потвърждение от сървъра \n", FILE_APPEND);
            file_put_contents('noSended.log', "entrance|{$card}|{$stamp}|NotCOnfirmed\n", FILE_APPEND);
        }
        $rfid = '';
    }
}