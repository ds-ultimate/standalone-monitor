<?php

// collects the current load avg

function get_load_data()
{
    /*
     * Load
     * 1min / 5min / 15min - /proc/loadavg column 1-3
     */

    $statsFile = fopen("/proc/loadavg", "r");
    $line=fgets($statsFile);
    if($line === false) {
        die("Unable to read file");
    }
    $line = trim(str_replace("\n", "", $line));
    while(str_contains($line, "  "))
        $line = str_replace("  ", " ", $line);

    $exp = explode(" ", $line);

    fclose($statsFile);

    return [
        "one" => floatval($exp[0]),
        "five" => floatval($exp[1]),
        "fifteen" => floatval($exp[2]),
    ];
}
