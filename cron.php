<?php
/*
Usage: 
/usr/bin/php -f /home/clickf/trunk/root/cron.php -- /cron/oasis_sync
*/

if (empty($argv[1])) exit;

$_SERVER['PATH_INFO'] = $argv[1];
$_SERVER['REQUEST_URI'] = $argv[1];

include('index.php');

/* End of file */
/* Location: ./ */