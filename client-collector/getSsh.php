<?php

// collects the count of open ssh sessions

function get_sshSession_data()
{
    /*
     *  (SSH Usage open sessions)
     *  ps -A x | grep [s]shd
     */
    
    $numSession = 0;
    $out = [];
    exec("ps -ax | grep [s]shd", $out);
    
    foreach($out as $line) {
        while(str_contains($line, "  "))
            $line = str_replace("  ", " ", $line);
        $line = trim($line);
        if($line == "") continue;
        
        $exploded = explode(" ", $line);
        if(count($exploded) < 6) continue;
        if($exploded[4] != "sshd:") continue;
        
        $shell = explode("@", $exploded[5]);
        if(count($shell) < 2) continue;
        if(! startsWith($shell[1], "pts")) continue;
        $numSession += 1;
    }

    return [
        "sessions" => $numSession
    ];
}
