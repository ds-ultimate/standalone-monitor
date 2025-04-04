<?php

// collects the current memory usage


function get_memory_data_inKB($val, $strExp) {
    switch($strExp) {
        case "MB":
            return intval(1000 * floatval($val));
        case "GB":
            return intval(1000 * 1000 * floatval($val));
        case "kB":
        default:
            return intval($val);
    }
}

function get_memory_data()
{

    /*
     * Memory /proc/meminfo
     * total memory -> MemTotal
     * fileCacheSize -> Active(file)+Inactive(file)
     * used (programms) -> MemTotal - MemFree - Buffers - Cached
     * used (buffers) -> Buffers
     * used (cache) -> Cached
     * free -> MemFree
     * claimable (not save) -> used (buffers) + used (cache) + free
     */
    
    $statsFile = fopen("/proc/meminfo", "r");
    
    $mapping = [
        "MemTotal:" => "mem_total",
        "MemFree:" => "free",
        "Buffers:" => "used_buffers",
        "Cached:" => "used_cache",
    ];
    
    $prog = 0;
    $fileCache = 0;

    $result_data = [];

    while(($line=fgets($statsFile))!==false) {
        $line = trim(str_replace("\n", "", $line));
        while(str_contains($line, "  "))
            $line = str_replace("  ", " ", $line);
        
        $exp = explode(" ", $line);
        
        if(array_key_exists($exp[0], $mapping)) {
            //will be written directly to Database
            $result_data[$mapping[$exp[0]]] = get_memory_data_inKB($exp[1], $exp[2]);
        }
        switch($exp[0]) {
            case "Active(file):":
            case "Inactive(file):":
                $fileCache += get_memory_data_inKB($exp[1], $exp[2]);
                break;
            case "MemTotal:":
                $prog += get_memory_data_inKB($exp[1], $exp[2]);
                break;
            case "MemFree:":
            case "Buffers:":
            case "Cached:":
                $prog -= get_memory_data_inKB($exp[1], $exp[2]);
                break;
        }
    }
    $result_data["file_cache_size"] = $fileCache;
    $result_data["used_programms"] = $prog;
    
    fclose($statsFile);

    return $result_data;
}
