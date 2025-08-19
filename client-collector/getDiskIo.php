<?php

// collects disk performance statistics

function get_diskio_data()
{
    global $AVAILABLE_DISKS;
    /*
     * File /proc/diskstats
     * Column 0: device id
     * Column 1: partition id
     * Column 2: partition name -> save diskname
     * Column 3: amount of reads completed -> save read_io
     * Column 4 amount of reads merged (e.g. merge two consecutiv 4k to a single 8k)
     * Column 5: amount of sectors read -> save read_sector (512 = sector size)
     * Column 6: amount of milliseconds read
     * Column 7: amount of writes completed -> save write_io
     * Column 8: amount of writes merged
     * Column 9: amount of sectors written -> save write_sector
     * Column 10: amount of milliseconds writing
     * Column 11: current I/O in progress
     * Column 12: current milliseconds spent on I/O
     * Column 13: current weighted milliseconds spent on I/O
     */
    
    $statsFile = fopen("/proc/diskstats", "r");
    $result_data = [];
    
    while(($line=fgets($statsFile))!==false) {
        $line = trim(str_replace("\n", "", $line));
        while(str_contains($line, "  "))
            $line = str_replace("  ", " ", $line);
        
        $exp = explode(" ", $line);

        if(in_array($exp[2], $AVAILABLE_DISKS)) {
            $result_data[] = [
                "diskname" => $exp[2],
                "r_io" => intval($exp[3]),
                "r_sector" => intval($exp[5]) / 2,
                "w_io" => intval($exp[7]),
                "w_sector" => intval($exp[9]) / 2,
            ];
        }
    }
    
    fclose($statsFile);

    return $result_data;
}
