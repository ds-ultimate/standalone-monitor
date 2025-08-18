<?php

// collects cpu statistics

function get_cpu_data()
{
    /*
     * Cpu /proc/stat | grep cpu
     * Column 0: cpu if all or cpu#id for the cores
     * Column 1: user mode code
     * Column 2: niced (low prio) user mode code
     * Column 3: kernel mode code
     * Column 4: idle
     * Column 5: iowait
     * Column 6: servicing interrupts
     * Column 7: servicing softirqs
     * Column 8: steal (time in other OS)
     * Column 9: guest (time used running guest code)
     * Column 10: guest_nice
     * All accumulated = whole time * 100 ("User_Hz")
     */
    
    $statsFile = fopen("/proc/stat", "r");
    $result_data = [];
    
    while(($line=fgets($statsFile))!==false) {
        $line = trim(str_replace("\n", "", $line));
        while(str_contains($line, "  "))
            $line = str_replace("  ", " ", $line);
        
        $exp = explode(" ", $line);

        // only submit values for all cores for now
        //if(startsWith($exp[0], "cpu"))
        if($exp[0] == "cpu") {
            $all = 0;
            for($i = 1; $i < 11; $i++) {
                $exp[$i] = intval($exp[$i]);
                $all+= $exp[$i];
            }

            $result_data[] = [
                "name" => $exp[0],
                "all_raw" => $all,
                "user_raw" => $exp[1],
                "user_niced_raw" => $exp[2],
                "kernel_raw" => $exp[3],
                "io_wait_raw" => $exp[5],
                "idle_raw" => $exp[4],
            ];
        }
    }
    
    fclose($statsFile);

    return $result_data;
}