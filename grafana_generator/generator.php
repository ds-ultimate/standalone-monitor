<?php

require_once "../config.php";
require_once "layouter.php";

require_once "dashboard.php";
require_once "row.php";
require_once "grafanaInterface.php";

require_once "panelParts/loadPanel.php";
require_once "panelParts/cpuUsagePanel.php";
require_once "panelParts/memoryUsagePanel.php";
require_once "panelParts/diskioPanel.php";
require_once "panelParts/diskusagePanel.php";
require_once "panelParts/networkPanel.php";
require_once "panelParts/sshPanel.php";


$severToGenerate = $SERVER_CONFIGURATION[0];
$dashboard = new Dashboard();
$globalDatasource = getGlobalDatasource();
$dashboard->addPanel((new Row($severToGenerate["name"])));
$availablePanels = $severToGenerate["has"];

if(in_array("load", $availablePanels)) {
    generateLoadPanel($dashboard, $globalDatasource, $severToGenerate["coreCnt"]);
}

if(in_array("cpu", $availablePanels)) {
    generateCpuUsagePanel($dashboard, $globalDatasource);
}

if(in_array("memory", $availablePanels)) {
    generateMemoryUsagePanel($dashboard, $globalDatasource);
}

if(in_array("diskio", $availablePanels)) {
    generateDiskioPanel($dashboard, $globalDatasource);
}

if(in_array("diskusage", $availablePanels)) {
    generateDiskusagePanel($dashboard, $globalDatasource);
}

if(in_array("network", $availablePanels)) {
    generatenetworkPanel($dashboard, $globalDatasource);
}

if(in_array("ssh", $availablePanels)) {
    generateSSHPanel($dashboard, $globalDatasource);
}

/*
    "sql" => [
        "type" => "single",
        "columns" => [
            ["bytes_received", "bytes_received", "i"],
            ["bytes_sent", "bytes_sent", "i"],
            ["handler_commit", "handler_commit", "i"],
            ["handler_delete", "handler_delete", "i"],
            ["handler_update", "handler_update", "i"],
            ["handler_write", "handler_write", "i"],
            ["innodb_data_read", "innodb_data_read", "i"],
            ["innodb_data_written", "innodb_data_written", "i"],
            ["innodb_data_reads", "innodb_data_reads", "i"],
            ["innodb_data_writes", "innodb_data_writes", "i"],
            ["queries", "queries", "i"],
            ["connections", "connections", "i"],
    
            ["innodb_buffer_pool_bytes_data", "innodb_buffer_pool_bytes_data", "i"],
            ["innodb_buffer_pool_pages_data", "innodb_buffer_pool_pages_data", "i"],
            ["Innodb_buffer_pool_pages_dirty", "Innodb_buffer_pool_pages_dirty", "i"],
            ["Innodb_buffer_pool_bytes_dirty", "Innodb_buffer_pool_bytes_dirty", "i"],
            ["innodb_buffer_pool_pages_free", "innodb_buffer_pool_pages_free", "i"],
            ["innodb_buffer_pool_pages_flushed", "innodb_buffer_pool_pages_flushed", "i"],
            ["innodb_mem_dictionary", "innodb_mem_dictionary", "i"],
    
            ["qcache_free_memory", "qcache_free_memory", "i"],
            ["qcache_hits", "qcache_hits", "i"],
            ["qcache_inserts", "qcache_inserts", "i"],
            ["qcache_not_cached", "qcache_not_cached", "i"],
            ["qcache_total_blocks", "qcache_total_blocks", "i"],
        ],
    ],
$dashboard->addPanel((new LayoutRow())
    // todo view that stuff and decide how to show
    ->addPanel(
        (new Timeseries("SQL Usage"))
        ->setSize(12, 8)
    )
);
*/

$dashboard->doLayout();
$result = $dashboard->generate();

file_put_contents("../panels.json", json_encode($result, JSON_PRETTY_PRINT));

performImport($result);
