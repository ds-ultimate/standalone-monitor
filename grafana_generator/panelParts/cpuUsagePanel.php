<?php

require_once "gauge.php";
require_once "timeseries.php";

require_once "apiDatasource.php";
require_once "dashboardDatasource.php";


function generateCpuUsagePanel($dashboard, $baseData) {
    $cpuDatasource = (new APIDatasource(globalDatasourcePart: $baseData, baseUrl: "series", table: "cpu",
            rows: ["all_raw", "user_raw", "user_niced_raw", "kernel_raw", "io_wait_raw", "idle_raw"]))
        ->addTransformationCalculate("user_raw", "/", "all_raw", "user")
        ->addTransformationCalculate("user_niced_raw", "/", "all_raw", "user_niced")
        ->addTransformationCalculate("kernel_raw", "/", "all_raw", "kernel")
        ->addTransformationCalculate("io_wait_raw", "/", "all_raw", "io_wait")
        ->addTransformationCalculate("idle_raw", "/", "all_raw", "idle")
        ->addTransformationFilterByPattern("^(?!.*_raw$).*");

    $cpuPanel = new Timeseries("CPU Usage", $cpuDatasource);
    $dashboard->addPanel((new LayoutRow())
        ->addPanel(
            $cpuPanel->setTooltipMode("multi")
        )

        ->addPanel(
            (new Gauge("CPU Usage", (new DashboardDatasource($cpuPanel))
                    ->addTransformationCalculate("user", "+", "user_niced", "tmp1")
                    ->addTransformationCalculate("tmp1", "+", "kernel", "tmp2")
                    ->addTransformationCalculate("tmp2", "+", "io_wait", "usage")
                    ->addTransformationFilterByName(["t", "usage"])))
                ->setMinMax(0, 1)
                ->setUnit("percentunit")
        )
    );
}
