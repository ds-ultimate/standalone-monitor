<?php

function isDiffCol($col, $diffRows, &$diffColCache) {
    if(! isset($diffColCache[$col])) {
        $parts = explode("__", $col);
        if(count($parts) <= 1) {
            $diffColCache[$col] = false;
        } else {
            $colIdentifier = $parts[1];
            //error_log("HERE $colIdentifier // " . json_encode($diffRows));
            $diffColCache[$col] = in_array($colIdentifier, $diffRows);
        }
    }
    return $diffColCache[$col];
}


function getNamedSeriesResult($query, $valid_rows) {
    global $database;

    $result = $database->query($query);

    if(! isset($_GET["nameCol"])) {
        http_response_code(404);
        die();
    }

    $nameRow_rawInput = $_GET["nameCol"];
    $nameRowEntry = null;
    $valid_rows_no_name = [];
    $diffRows = [];

    foreach($valid_rows as $val_row) {
        if($val_row[0] != $nameRow_rawInput) {
            $valid_rows_no_name[] = $val_row;
        } else {
            $nameRowEntry = $val_row;
        }

        if($val_row[2] == "id" || $val_row[2] == "fd") {
            $diffRows[] = $val_row[0];
        }
    }

    if($nameRowEntry === null) {
        http_response_code(404);
        die();
    }

    $aggregatedData = [];
    while ($row = $result->fetch_assoc()) {
        $timestep = (int) $row["t"];

        if(! isset($aggregatedData[$timestep])) {
            $aggregatedData[$timestep] = [
                "t" => $timestep,
            ];
        }

        $entry_name = $row[$nameRowEntry[0]];
        foreach($valid_rows_no_name as $valRow) {
            if($valRow[2] == "i" || $valRow[2] == "id") {
                $value = intval($row[$valRow[0]]);
            } else if($valRow[2] == "f" || $valRow[2] == "fd") {
                $value = floatval($row[$valRow[0]]);
            } else {
                $value = $row[$valRow[0]];
            }
            $aggregatedData[$timestep]["{$entry_name}__{$valRow[0]}"] = $value;
        }
    }
    $result->close();

    $diffColCache = [];
    $lastRow = null;
    $convertedData = [];
    foreach($aggregatedData as $agrRow) {
        if($lastRow == null) {
            $lastRow = $agrRow;
            continue;
        }

        $tmp = [];

        foreach($agrRow as $key => $val) {
            if(isDiffCol($key, $diffRows, $diffColCache)) {
                if(! isset($lastRow[$key])) {
                    continue;
                }
                $timeDiff = $agrRow["t"] - $lastRow["t"];
                $val = ($val - $lastRow[$key]) / $timeDiff;
            }
            $tmp[$key] = $val;
        }
        $convertedData[] = $tmp;
        $lastRow = $agrRow;
    }

    return $convertedData;
}