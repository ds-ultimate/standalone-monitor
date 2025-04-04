<?php

include_once "helper_functions.php";

if(! isset($_SERVER["REQUEST_METHOD"])) exit_with_code(404);
if($_SERVER["REQUEST_METHOD"] !== "POST") exit_with_code(404);

include_once "../config.php";

$server_id = filter_input(INPUT_GET, "server_id", FILTER_VALIDATE_INT);

if(! isset($SERVER_CONFIGURATION[$server_id])) exit_with_code(401);

$server_settings = $SERVER_CONFIGURATION[$server_id];

# access control
if(! isset($_SERVER["HTTP_API_KEY"])) exit_with_code(401);
if($_SERVER["HTTP_API_KEY"] !== $server_settings["key"]) exit_with_code(401);


include_once "../shared/collector_config.php";
include_once "../shared/mysql_interface.php";

$database = new MysqlInterface($MYSQL_DB_NAME);


$json = file_get_contents('php://input');
$raw_data = json_decode($json, true);


function build_query_header($cols) {
    $result = " (`server_id`, `time`, `zoombase`";
    foreach($cols as $colEntry) {
        $result .= ", `" . $colEntry[1] . "`";
    }
    $result .= ")";
    return $result;
}


function build_query_parts_single(&$query, &$bindings, $raw_entry, $cols, $globalPart) {
    $query .= " (" . $globalPart;
    foreach($cols as $colEntry) {
        if(! isset($raw_entry[$colEntry[0]])) {
            echo "Missing part {$colEntry[0]}\ņ";
            return false;
        }

        $query .= ", ";
        if($colEntry[2] == "s") {
            $query .= "?";
            $bindings[] = $raw_entry[$colEntry[0]];
        } elseif($colEntry[2] == "f") {
            $query .= filter_var($raw_entry[$colEntry[0]], FILTER_VALIDATE_FLOAT);
        } else {
            $query .= filter_var($raw_entry[$colEntry[0]], FILTER_VALIDATE_INT);
        }
    }
    $query .= ")";
    return true;
}


function generic_import_data($typeName, $globalPart) {
    global $database, $COLLECTOR_CONFIG, $raw_data;

    if(! isset($raw_data[$typeName])) {
        die("ERR 1");
        return;
    }

    $raw_part = $raw_data[$typeName];
    $expected = $COLLECTOR_CONFIG[$typeName];

    $query = "INSERT INTO `$typeName`" . build_query_header($expected["columns"]) . " VALUES";
    $bindings = [];

    if($expected["type"] == "single") {
        $retval = build_query_parts_single($query, $bindings, $raw_part, $expected["columns"], $globalPart);
        if($retval === false) {
            return;
        }
    } else {
        $first = true;
        foreach($raw_part as $singleEntry) {
            if(! $first) $query .= ",";
            $retval = build_query_parts_single($query, $bindings, $singleEntry, $expected["columns"], $globalPart);
            if($retval === false) {
                return;
            }
            $first = false;
        }
    }

    $database->prepared_insert($query, $bindings);
}


// server_id has been generated by filter_id
$lastZoomEntry = $database->querySingle("SELECT `server_id`, `time` FROM `servers` WHERE `server_id` = $server_id");
$curTime = filter_var($raw_data["time"], FILTER_VALIDATE_INT);

$sorted_zoom = array_keys($ZOOM_CONFIG);
sort($sorted_zoom);

if($lastZoomEntry === false || $lastZoomEntry === null) {
    $zoombase = $sorted_zoom[0];
} else {
    $lastTime = $lastZoomEntry["time"];
    $zoombase = $sorted_zoom[count($sorted_zoom) - 1];

    foreach($sorted_zoom as $zoKey) {
        $zoomDiff = $ZOOM_CONFIG[$zoKey];
        $target = ((int) ($lastTime / $zoomDiff) + 1) * $zoomDiff;
        if($curTime >= $target) {
            $zoombase = $zoKey;
            break;
        }
    }
}

$database->query("REPLACE INTO `servers` (`server_id`, `time`) VALUES ($server_id, $curTime)");

// server_id has been generated by filter_id
$globalPart = "$server_id, $curTime, $zoombase";

foreach($server_settings["has"] as $collectorName) {
    generic_import_data($collectorName, $globalPart);
}

echo "OK";
