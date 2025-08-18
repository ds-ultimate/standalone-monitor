<?php

require_once "layouter.php";

require_once "dashboard.php";
require_once "row.php";
require_once "gauge.php";
require_once "timeseries.php";

require_once "apiDatasource.php";


$dashboard = new Dashboard();

$dashboard->addPanel((new Row("Server 1")));
$dashboard->addPanel((new LayoutRow())
    ->addPanel(
        new Timeseries("Load", new APIDatasource(baseUrl: "series", table: "load", rows: ["one", "five", "fifteen"]))
    )
    ->addPanel(
        new Gauge("Load")
        //->setDatasource(new APIDatasource(baseUrl: "gauge", table: "load", rows: ["oneMin", "fiveMin", "fifteenMin"]))
    )
);

$cpuDatasource = (new APIDatasource(baseUrl: "series", table: "cpu",
        rows: ["all_raw", "user_raw", "user_niced_raw", "kernel_raw", "io_wait_raw", "idle_raw"]))
    ->addTransformationCalculate("user_raw", "/", "all_raw", "user")
    ->addTransformationCalculate("user_niced_raw", "/", "all_raw", "user_niced")
    ->addTransformationCalculate("kernel_raw", "/", "all_raw", "kernel")
    ->addTransformationCalculate("io_wait_raw", "/", "all_raw", "io_wait")
    ->addTransformationCalculate("idle_raw", "/", "all_raw", "idle")
    ->addTransformationFilterByName("^(?!.*_raw$).*");

$dashboard->addPanel((new LayoutRow())
    ->addPanel(
        new Timeseries("CPU Usage", $cpuDatasource)
    )

    ->addPanel(
        new Gauge("CPU Usage")
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
