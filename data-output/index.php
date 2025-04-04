<?php

if(! isset($_SERVER["REQUEST_METHOD"]) || $_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(404);
    die();
}

include_once "../config.php";

# access control
if(! isset($_SERVER["HTTP_API_KEY"]) || $_SERVER["HTTP_API_KEY"] !== $GRAFANA_API_KEY) {
    http_response_code(401);
    die();
}

# validations
$valid_date_from = filter_input(INPUT_GET, "date_from", FILTER_VALIDATE_INT);
$valid_date_to = filter_input(INPUT_GET, "date_to", FILTER_VALIDATE_INT);

$raw_path = explode("?", $_SERVER["REQUEST_URI"], 2)[0];

$data = [
    "PATH" => $raw_path,
    "date_from" => $valid_date_from,
    "date_to" => $valid_date_to,
];


# fake data for now
$result = [];
$last = 50;
$last2 = 50;
for($i = $valid_date_from; $i < $valid_date_to; $i+=1000) {
    $last = max(min($last + random_int(-2, 2), 100), 0);
    $last2 = max(min($last2 + random_int(-2, 2), 100), 0);
    $result[] = [
        "t" => $i,
        "a" => $last,
        "b" => $last2,
    ];
}

$encoded = json_encode($result);
echo($encoded);
die();

//query form sql

//send queried data back

$data = [
    "PATH" => $raw_path,
    "date_from" => $valid_date_from,
    "date_to" => $valid_date_to,
];

$encoded = json_encode($data);
echo($encoded);