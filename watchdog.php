<?php
exec("ps -A | grep php", $output, $returnVar);
$cntOK = 0;
$cntAll = count($output);

foreach ($output as $res) {
    if (strpos($res,'?') && strpos($res, '00:00:00')) {
        $cntOK++;
    }
}
if ($cntOK != $cntAll) {
	file_put_contents('debug.log', date("Y-m-d H:i:s") . " - Restart ... \n", FILE_APPEND);
	exec ('sudo reboot');
} else {
	// ne restartirame
	// echo ("ne restartirame\n");
}

exit (0);

// print_r ($output);
// echo ($returnVar);
