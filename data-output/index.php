<?php
require_once "../shared/helper_functions.php";
require_once "../shared/collector_config.php";

if(! isset($_SERVER["REQUEST_METHOD"]) || $_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(404);
    die();
}

require_once "../config.php";

# access control
if(! isset($_SERVER["HTTP_API_KEY"]) || $_SERVER["HTTP_API_KEY"] !== $GRAFANA_API_KEY) {
   http_response_code(401);
   die();
}


# validations
$valid_date_from = (int) (filter_input(INPUT_GET, "date_from", FILTER_VALIDATE_INT) / 1000);
$valid_date_to = (int) (filter_input(INPUT_GET, "date_to", FILTER_VALIDATE_INT) / 1000);
if($valid_date_to <= $valid_date_from) {
    http_response_code(422);
    die("To must be bigger than from");
}

if(! isset($_GET["table"]) || ! isset($_GET["row"])) {
    http_response_code(404);
    die();
}

$tables = array_keys($COLLECTOR_CONFIG);
$table_idx = array_search($_GET["table"], $tables);
if($table_idx === false) {
    http_response_code(404);
    die();
}
$valid_table = $tables[$table_idx];


$valid_rows = [];
foreach($COLLECTOR_CONFIG[$valid_table]["columns"] as $colArr) {
    if(in_array($colArr[0], $_GET["row"])) {
        $valid_rows[] = $colArr;
    }
}

if(count($valid_rows) < 1) {
    http_response_code(404);
    die();
}


require_once "../shared/mysql_interface.php";
$database = new MysqlInterface($MYSQL_DB_NAME);

$query_rows = "`time`*1000 as `t`";
foreach($valid_rows as $row) {
    $query_rows.= ",`{$row[1]}`as`{$row[0]}`";
}

$zoomlevelPart = "";
$sorted_zoom = array_keys($ZOOM_CONFIG);
rsort($sorted_zoom);
$curLevel = $sorted_zoom[0];

if($valid_date_to - $valid_date_from > $MAX_POINTS) {
    //virtual zoom "out" needed to reduce amount of points
    $rawRangeSeconds = $valid_date_to - $valid_date_from;

    $curLevelId = 0;
    while($curLevelId < count($sorted_zoom)) {
        $curLevel = $sorted_zoom[$curLevelId];
        if($rawRangeSeconds / $ZOOM_CONFIG[$curLevel] < $MAX_POINTS) {
            break;
        }
        $curLevelId++;
    }

    $zoomlevelPart = " AND `zoombase` <= $curLevel";
}

//reduce $valid_date_from by one step or at least 10s
$valid_date_from -= max($ZOOM_CONFIG[$curLevel], 10);


$query = "SELECT $query_rows FROM `$valid_table` WHERE $valid_date_from < `time` AND `time` < $valid_date_to$zoomlevelPart";


$raw_path = explode("?", $_SERVER["REQUEST_URI"], 2)[0];
if($raw_path == "/series") {
    require_once "seriesResult.php";
    $convertedData = getSeriesResult($query, $valid_rows);
} else if($raw_path == "/namedSeries") {
    require_once "namedSeriesResult.php";
    $convertedData = getNamedSeriesResult($query, $valid_rows);
} else if($raw_path == "/diskusageNamedSeries") {
    require_once "diskUsageNamedSeriesResult.php";
    $convertedData = getDiskUsageNamedSeriesResult($query, $valid_rows);
} else {
    $convertedData = [];
}

$encoded = json_encode($convertedData);
echo($encoded);
