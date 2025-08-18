<?php

require_once "gauge.php";
require_once "timeseries.php";

require_once "apiDatasource.php";
require_once "dashboardDatasource.php";


function generateLoadPanel($dashboard, $baseData, $cpuCnt) {
    $loadPanel = new Timeseries("Load", new APIDatasource(globalDatasourcePart: $baseData, baseUrl: "series", table: "load", rows: ["one", "five", "fifteen"]));
    $dashboard->addPanel((new LayoutRow())
        ->addPanel(
            $loadPanel->setTooltipMode("multi")
        )
        ->addPanel(
            (new Gauge("Load", (new DashboardDatasource($loadPanel))
                    ->addTransformationFilterByName(["t", "one"])))
                ->setMinMax(0, $cpuCnt)
        )
    );
}
