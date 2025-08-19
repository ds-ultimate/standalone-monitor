<?php

// collects the current memory usage


function get_memory_data_inKB($rawData) {
    $parts = explode(" ", trim($rawData));
    $sizeDelimiter = "";
    if(count($parts) > 1) {
        $sizeDelimiter = $parts[1];
    }

    switch($sizeDelimiter) {
        case "MB":
            return intval(1000 * floatval($parts[0]));
        case "GB":
            return intval(1000 * 1000 * floatval($parts[0]));
        case "kB":
        default:
            return intval($parts[0]);
    }
}

function parseMeminfo() {
    $meminfo = [];
    foreach (file('/proc/meminfo') as $line) {
        $parts = explode(':', $line);
        $meminfo[$parts[0]] = get_memory_data_inKB($parts[1]);
    }
    return $meminfo;
}

function get_memory_data()
{
    /*
     * Memory /proc/meminfo
     * total memory -> MemTotal
     * used (programms) -> MemTotal - MemFree - Buffers - Cached
     * used (buffers) -> Buffers
     * used (cache) -> Cached
     * free -> MemFree
     * claimable (not save) -> used (buffers) + used (cache) + free
     */

    $meminfo = parseMeminfo();
    $result_data = [];
    $result_data["mem_total"] = $meminfo['MemTotal'];
    $result_data["free"] = $meminfo['MemFree'];
    $result_data["used_buffers"] = $meminfo['Buffers'];
    $result_data["used_cache"] = $meminfo['Cached'] + $meminfo['SReclaimable'] - $meminfo['Shmem'];
    $result_data["used_programs"] = $result_data["mem_total"] - $result_data["free"] -
            $result_data["used_buffers"] - $result_data["used_cache"];
    return $result_data;
}
