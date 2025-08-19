<?php

// collects the current network usage

function get_network_data()
{
    global $ALLOWED_NETWORK_INTERFACES;
    /*
     * Netzwerk
     * Interfaces: "ip -oneline link show" oder "ifconfig" oder ls /sys/class/net/
     * Gesendet Bytes -> /sys/class/net/[interface]/statistics/tx_bytes
     * Empfangen Bytes -> /sys/class/net/[interface]/statistics/rx_bytes
     * Gesendet Pakete -> /sys/class/net/[interface]/statistics/tx_packets
     * Empfangen Pakete -> /sys/class/net/[interface]/statistics/rx_packets
     */

    $result_data = [];
    
    foreach(scandir("/sys/class/net") as $int) {
        if(! in_array($int, $ALLOWED_NETWORK_INTERFACES))
            continue;

        $result_data[] = [
            "interface" => $int,
            "sent_bytes" => intval(file_get_contents("/sys/class/net/$int/statistics/tx_bytes")),
            "received_bytes" => intval(file_get_contents("/sys/class/net/$int/statistics/rx_bytes")),
            "sent_packets" => intval(file_get_contents("/sys/class/net/$int/statistics/tx_packets")),
            "received_packets" => intval(file_get_contents("/sys/class/net/$int/statistics/rx_packets")),
        ];
    }

    return $result_data;
}
