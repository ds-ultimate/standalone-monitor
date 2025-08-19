<?php

require_once "timeseries.php";
require_once "apiDatasource.php";


function generateSSHPanel($dashboard, $baseData) {
    $dashboard->addPanel((new LayoutRow())
        ->addPanel(
            (new Timeseries("SSH sessions", new APIDatasource(globalDatasourcePart: $baseData, table: "ssh", rows: ["sessions"])))
                ->setSize(12, 8)
                ->setTooltipMode("multi")
        )
    );
}
