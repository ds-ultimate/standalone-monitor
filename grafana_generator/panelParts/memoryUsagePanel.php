<?php

require_once "gauge.php";
require_once "timeseries.php";

require_once "apiDatasource.php";
require_once "dashboardDatasource.php";


function generateMemoryUsagePanel($dashboard, $baseData) {
    $datasource = (new APIDatasource(globalDatasourcePart: $baseData, baseUrl: "memorySeries", table: "memory",
            rows: ["used_programs", "used_buffers", "used_cache", "file_cache_size", "free"]))
        ->addTransformationSortByName(["used_programs", "file_cache_size", "used_cache", "used_buffers", "free", "t"]);


    $dashboard->addPanel((new LayoutRow())
        ->addPanel(
            (new Timeseries("Memory Usage", $datasource))
                ->setUnit("kbytes")->setGraphType("bars")->setDisplayPoints("never")
                ->setStackingMode("normal")->setTooltipMode("multi")
        )
        ->addPanel(
            (new Gauge("Memory Usage", new APIDatasource(globalDatasourcePart: $baseData, baseUrl: "series", table: "memory",
                    rows: ["used_programs", "mem_total"])))
                ->setUnit("kbytes")->setFields("used_programs")
        )
    );
}
