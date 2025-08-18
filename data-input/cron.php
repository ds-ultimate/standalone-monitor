<?php

require_once "../config.php";
require_once "../shared/collector_config.php";
require_once "../shared/mysql_interface.php";
require_once "../shared/helper_functions.php";


$database = new MysqlInterface($MYSQL_DB_NAME);
$curTime = time();

foreach($COLLECTOR_CONFIG as $name => $val) {
    foreach($RETENTION_POLICY as $zoomlevel => $retentSec) {
        $effectiveTime = $curTime - $retentSec;
        $query = "DELETE FROM `$name` WHERE `time` < $effectiveTime AND `zoombase` >= $zoomlevel";
        $database->query($query);
    }
}

echo "Delete ok\n";
