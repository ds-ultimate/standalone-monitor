<?php

require_once "gauge.php";
require_once "timeseries.php";

require_once "apiDatasource.php";


function generateMemoryUsagePanel($dashboard, $baseData) {
    $datasource = (new APIDatasource(globalDatasourcePart: $baseData, table: "memory",
            rows: ["used_programs", "used_buffers", "used_cache", "free"]))
        ->addTransformationSortByName(["used_programs", "used_cache", "used_buffers", "free", "t"]);

    $dashboard->addPanel((new LayoutRow())
        ->addPanel(
            (new Timeseries("Memory Usage", $datasource))
                ->setUnit("kbytes")->setLineWidth(0)->setFillOpacity(100)->setDisplayPoints("never")
                ->setStackingMode("normal")->setTooltipMode("multi")
        )
        ->addPanel(
            (new Gauge("Memory Usage", new APIDatasource(globalDatasourcePart: $baseData, table: "memory",
                    rows: ["used_programs", "mem_total"])))
                ->setUnit("kbytes")->setFields("used_programs")->setMinMax(0, null)
        )
    );
}
