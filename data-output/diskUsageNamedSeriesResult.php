<?php

require_once "namedSeriesResult.php";

function getDiskUsageNamedSeriesResult($query, $valid_rows) {
    $modifiedData = getNamedSeriesResult($query, $valid_rows);

    $convertedData = array_map(function ($elm) {
        $newElm = [];

        foreach(array_keys($elm) as $key) {
            if(endsWith($key, "__kb_all")) {
                $newKey = substr($key, 0, strlen($key) - 8);
                $newElm[$newKey] = $elm[$newKey . "__kb_used"] / $elm[$newKey . "__kb_all"];
            } else if(endsWith($key, "__kb_used")) {
                // ignore already processed by previous part
            }
            else {
                $newElm[$key] = $elm[$key];
            }
        }
        return $newElm;
    }, $modifiedData);
    return $convertedData;
}