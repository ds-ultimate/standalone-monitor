<?php

require_once "timeseries.php";

require_once "apiNamedDatasource.php";


function generateDiskioPanel($dashboard, $baseData) {
    $dataSrcSector = (new APINamedDatasource(globalDatasourcePart: $baseData, nameRow: "diskname", table: "diskio",
            rows: ["diskname", "r_sector", "w_sector"]))
        ->addTransformationRenameRegex("(.*)__r_sector", "\$1_read")
        ->addTransformationRenameRegex("(.*)__w_sector", "\$1_write");

    $dataSrcBytes = (new APINamedDatasource(globalDatasourcePart: $baseData, nameRow: "diskname", table: "diskio",
            rows: ["diskname", "r_io", "w_io"]))
        ->addTransformationRenameRegex("(.*)__r_io", "\$1_read")
        ->addTransformationRenameRegex("(.*)__w_io", "\$1_write");

    $dashboard->addPanel((new LayoutRow())
        ->addPanel(
            (new Timeseries("Disk data/s", $dataSrcSector))
            ->setSize(12, 8)
            ->setUnit("kbytes")
        )
        ->addPanel(
            (new Timeseries("Disk iops", $dataSrcBytes))
            ->setSize(12, 8)
        )
    );
}
