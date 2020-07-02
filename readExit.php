<?php 
require_once 'config.php';
require_once 'em4100Decode.php';
// вход :'1001'
// изход:'1002'

// $stamp = YYYYMMDDHHMMSS
$fp = fopen(EXIT_PORT, 'r');
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
        
        $exitUrl = str_replace($search,$replace,EXIT_URL);
        
        $res = @file_get_contents($exitUrl);
        
        if (FALSE === $res) {
            // Неработещо УРЛ
            file_put_contents('debug.log', date("Y-m-d H:i:s") . " " . basename(__FILE__). " " . " - {$card} - Неработещо УРЛ \n", FILE_APPEND);
            file_put_contents('noSended.log', "exit|{$card}|{$stamp}\n", FILE_APPEND);
            continue;
        }
        if (strpos($res,'inserted')!==FALSE) {
            // Въведен запис в BGERpa
            file_put_contents('debug.log', date("Y-m-d H:i:s") . " " . basename(__FILE__). " " ." - $card - $stamp - Въведен запис в BGERpa \n", FILE_APPEND);
            
        } else {
                // Warning
            file_put_contents('debug.log', date("Y-m-d H:i:s") .  " " . basename(__FILE__). " ". $exitUrl ." - $card - $stamp - Неполучено потвърждение от сървъра \n", FILE_APPEND);
            file_put_contents('noSended.log', "exit|{$card}|{$stamp}\n", FILE_APPEND);
        }
        $rfid = '';
        // рестартираме порта
        fclose($fp);
        usleep(100000); // 0.1 sec
        $fp = fopen(EXIT_PORT, 'r');
    }
}