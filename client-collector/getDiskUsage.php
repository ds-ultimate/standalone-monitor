<?php

// collects current file system usage

function get_diskusage_data()
{
    global $AVAILABLE_DISKS_USAGE;
    /*
     * 1. /proc/mounts
     * 1-> type
     * 2-> location
     * 
     * 2. stat --file-system -c "%S %b %f %a %c %d"
     * %S ... block größe
     * %b ... gesBlöcke
     * %f ... freiBlöcke (super user)
     * %a ... freiBlöcke (non super user)
     * %c ... gesInodes
     * %d ... freiInodes
     * 
     * %f - %a = reserved
     */

    $mountsFile = fopen("/proc/mounts", "r");
    $result_data = [];

    while(($line=fgets($mountsFile))!==false) {
        $line = trim(str_replace("\n", "", $line));
        while(str_contains($line, "  "))
            $line = str_replace("  ", " ", $line);

        $exp = explode(" ", $line);

        if(in_array($exp[0], $AVAILABLE_DISKS_USAGE)) {
            $fsStats = explode(" ", exec('stat --file-system -c "%S %b %f %a %c %d" \''.$exp[1].'\''));
            $block = floatval($fsStats[0]) / 1024;

            $result_data[] = [
                "diskname" => str_replace("/dev/", "", $exp[0]),
                "mounted_at" => $exp[1],
                "kb_all" => intval($fsStats[1]) * $block,
                "kb_used" => (intval($fsStats[1]) - intval($fsStats[2])) * $block,
                "kb_reserved" => (intval($fsStats[2]) - intval($fsStats[3])) * $block,
                "in_all" => intval($fsStats[4]),
                "in_used" => intval($fsStats[4]) - intval($fsStats[5]),
            ];
        }
    }

    fclose($mountsFile);

    return $result_data;
}
