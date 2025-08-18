<?php

require_once "layouter.php";

require_once "dashboard.php";
require_once "row.php";
require_once "gauge.php";
require_once "timeseries.php";

require_once "apiDatasource.php";
require_once "dashboardDatasource.php";


$cpuCnt = 12;

$dashboard = new Dashboard();

$dashboard->addPanel((new Row("Server 1")));

$loadPanel = new Timeseries("Load", new APIDatasource(baseUrl: "series", table: "load", rows: ["one", "five", "fifteen"]));
$dashboard->addPanel((new LayoutRow())
    ->addPanel(
        $loadPanel
    )
    ->addPanel(
        (new Gauge("Load", (new DashboardDatasource($loadPanel))
                ->addTransformationFilterByName(["t", "one"])))
            ->setMinMax(0, $cpuCnt)
    )
);

$cpuDatasource = (new APIDatasource(baseUrl: "series", table: "cpu",
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
        $cpuPanel
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
/*
$dashboard->addPanel((new LayoutRow())
    ->addPanel(
        (new Timeseries("Memory Usage"))
    )
    ->addPanel(
        (new Gauge("Memory Usage"))
    )
);
$dashboard->addPanel((new LayoutRow())
    ->addPanel(
        (new Timeseries("Disk IO Usage sectors"))
        ->setSize(12, 8)
    )
    ->addPanel(
        (new Timeseries("Disk IO Usage bytes"))
        ->setSize(12, 8)
    )
);
$dashboard->addPanel((new LayoutRow())
    ->addPanel(
        (new Timeseries("Disk Usage"))
    )
    ->addPanel(
        (new Gauge("Disk Usage"))
    )
);
$dashboard->addPanel((new LayoutRow())
    ->addPanel(
        (new Timeseries("Network Usage PPS"))
        ->setSize(12, 8)
    )
    ->addPanel(
        (new Timeseries("Network Usage bytes"))
        ->setSize(12, 8)
    )
);
$dashboard->addPanel((new LayoutRow())
    ->addPanel(
        (new Timeseries("SSH sessions"))
        ->setSize(12, 8)
    )
);
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
