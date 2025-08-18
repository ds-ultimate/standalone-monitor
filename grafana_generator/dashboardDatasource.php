<?php

require_once "datasource.php";
require_once "dashboard.php";


class DashboardDatasource extends Datasource {
    private DashboardPanel $otherPanel;

    public function __construct(DashboardPanel $otherPanel) {
        $this->otherPanel = $otherPanel;
    }

    public function generate() {
        $rawData = "
{
    \"datasource\": {
        \"type\": \"datasource\",
        \"uid\": \"-- Dashboard --\"
    },
    \"targets\": [
        {
            \"datasource\": {
                \"type\": \"datasource\",
                \"uid\": \"-- Dashboard --\"
            },
            \"panelId\": -1,
            \"refId\": \"A\",
            \"withTransforms\": true
        }
    ],
    \"transformations\": []
}
";
        $tmp = json_decode($rawData, true);
        if($tmp == NULL) {
            die("internal error dashboardDatasource\n");
        }

        $tmp["targets"][0]["panelId"] = $this->otherPanel->getPanelId();
        $tmp["transformations"] = array_merge($tmp["transformations"], $this->additionalTransformations);
        return $tmp;
    }
}
