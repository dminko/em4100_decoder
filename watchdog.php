<?php
exec("ps -A | grep php", $output, $returnVar);
$cnt = 0;
foreach ($output as $res) {
	if (strpos($res,'?') && strpos($res, '00:00:00')) {
		$cnt++;
	}
}
if ($cnt < 2) {
	exec ('sudo reboot');
} elseif ($cnt ==2) {
	// ne restartirame
	echo ("ne restartirame\n");
}



// print_r ($output);
// echo ($returnVar);
