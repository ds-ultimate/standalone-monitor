<?php

require_once "gauge.php";
require_once "timeseries.php";

require_once "apiNamedDatasource.php";
require_once "dashboardDatasource.php";


function generatenetworkPanel($dashboard, $baseData) {
    $dataSrcPPS = (new APINamedDatasource(globalDatasourcePart: $baseData, nameRow: "interface", table: "network",
            rows: ["interface", "sent_packets", "received_packets"]))
        ->addTransformationRenameRegex("(.*)__sent_packets", "\$1_sent")
        ->addTransformationRenameRegex("(.*)__received_packets", "\$1_received");

    $dataSrcBytes = (new APINamedDatasource(globalDatasourcePart: $baseData, nameRow: "interface", table: "network",
            rows: ["interface", "sent_bytes", "received_bytes"]))
        ->addTransformationRenameRegex("(.*)__sent_bytes", "\$1_sent")
        ->addTransformationRenameRegex("(.*)__received_bytes", "\$1_received");

    $dashboard->addPanel((new LayoutRow())
        ->addPanel(
            (new Timeseries("Network Usage PPS", $dataSrcPPS))
            ->setSize(12, 8)
        )
        ->addPanel(
            (new Timeseries("Network Usage bytes", $dataSrcBytes))
            ->setSize(12, 8)
        )
    );
}
