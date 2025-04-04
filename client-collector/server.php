<?php

include_once "config.php";
include_once "shared/helper_functions.php";
include_once "shared/mysql_interface.php";
include_once "getCpu.php";
include_once "getDiskIo.php";
include_once "getDiskUsage.php";
include_once "getLoad.php";
include_once "getMemory.php";
include_once "getNetwork.php";
include_once "getSql.php";
include_once "getSsh.php";


$url = "$BASE_URL/?server_id=$SERVER_ID";

if(in_array("sql", $SERVER_CAPABILITIES)) {
    $database = new MysqlInterface();
}

$COLLECTOR_FUNCTIONS = [
    "cpu" => "get_cpu_data",
    "diskio" => "get_diskio_data",
    "diskusage" => "get_diskusage_data",
    "load" => "get_load_data",
    "memory" => "get_memory_data",
    "network" => "get_network_data",
    "sql" => "get_sql_data",
    "ssh" => "get_sshSession_data",
];


$startTime = time();
$diff = 0;
$curTime = time();

while($diff < $CRON_INTERVAL) {
    $data = [
        "time" => $curTime,
    ];
    
    foreach($SERVER_CAPABILITIES as $cap) {
        $data[$cap] = call_user_func($COLLECTOR_FUNCTIONS[$cap]);
    }
    
    $authPart = "";
    if($AUTH_HEADER !== null) {
        $authPart = "Authorisation: $AUTH_HEADER\r\n";
    }
    
    $options = [
        'http' => [
            'header' => "Content-type: application/json\r\nAPI-KEY: $API_KEY\r\n$authPart",
            'method' => 'POST',
            'content' => json_encode($data),
        ],
    ];
    
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === false) {
        /* Handle error */
    }
    
    echo $curTime . "//" . json_encode($result) . "\n";

    sleep($SEND_INTERVAL);
    $curTime = time();
    $diff = $curTime - $startTime;
}
