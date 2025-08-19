<?php

function getSeriesResult($query, $valid_rows) {
    global $database;

    $result = $database->query($query);

    $convertedData = [];
    $lastRow = null;
    while ($row = $result->fetch_assoc()) {
        if($lastRow == null) {
            $lastRow = $row;
            continue;
        }

        $tmp = [
            "t" => (int) $row["t"],
        ];

        foreach($valid_rows as $valRow) {
            if($valRow[2] == "i") {
                $tmp[$valRow[0]] = intval($row[$valRow[0]]);
            } else if($valRow[2] == "f") {
                $tmp[$valRow[0]] = floatval($row[$valRow[0]]);
            } else if($valRow[2] == "id") {
                $timeDiff = intval($row["t"]) - intval($lastRow["t"]);
                $tmp[$valRow[0]] = (intval($row[$valRow[0]]) - intval($lastRow[$valRow[0]])) / $timeDiff;
            } else if($valRow[2] == "fd") {
                $timeDiff = intval($row["t"]) - intval($lastRow["t"]);
                $tmp[$valRow[0]] = (floatval($row[$valRow[0]]) - floatval($lastRow[$valRow[0]])) / $timeDiff;
            } else {
                $tmp[$valRow[0]] = $row[$valRow[0]];
            }
        }
        $convertedData[] = $tmp;
        $lastRow = $row;
    }
    $result->close();

    return $convertedData;
}