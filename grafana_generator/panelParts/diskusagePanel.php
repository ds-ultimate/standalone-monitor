<?php

require_once "gauge.php";
require_once "timeseries.php";

require_once "apiNamedDatasource.php";
require_once "dashboardDatasource.php";


function generateDiskusagePanel($dashboard, $baseData) {
    $dataSrc = (new APINamedDatasource(globalDatasourcePart: $baseData, nameRow: "mounted_at", table: "diskusage",
            rows: ["mounted_at", "kb_used", "kb_all"], baseUrl: "diskusageNamedSeries"));

    $panelTimeseries = new Timeseries("Disk usage", $dataSrc);

    $dashboard->addPanel((new LayoutRow())
        ->addPanel(
            $panelTimeseries
            ->setSize(12, 8)
            ->setUnit("percentunit")
        )
        ->addPanel(
            (new Gauge("Disk usage", (new DashboardDatasource($panelTimeseries))))
            ->setUnit("percentunit")
            ->setSize(12, 8)
            ->setMinMax(0, 1)
        )
    );
}
