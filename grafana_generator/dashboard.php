<?php

require_once "layouter.php";

abstract class DashboardPanel extends LayoutElement {
    private $panelId = -1;
    private $title;

    function __construct($title)
    {
        $this->title = $title;
    }

    function addIdTitlePos($rawArray) {
        $rawArray["title"] = $this->title;
        $rawArray["id"] = $this->panelId;

        return $this->addPos($rawArray);
    }

    function setPanelId($panelId) {
        $this->panelId = (int) $panelId;
    }

    function getPanelId() {
        return $this->panelId;
    }

    public abstract function generate();
}


class Dashboard extends LayoutManager {
    public function generate() {
        $rawData = "
{
    \"__inputs\": [
        {
            \"name\": \"DS_YESOREYERAM-INFINITY-DATASOURCE\",
            \"label\": \"yesoreyeram-infinity-datasource\",
            \"description\": \"\",
            \"type\": \"datasource\",
            \"pluginId\": \"yesoreyeram-infinity-datasource\",
            \"pluginName\": \"Infinity\"
        }
    ],
    \"__elements\": {},
    \"__requires\": [
        {
            \"type\": \"panel\",
            \"id\": \"gauge\",
            \"name\": \"Gauge\",
            \"version\": \"\"
        },
        {
            \"type\": \"grafana\",
            \"id\": \"grafana\",
            \"name\": \"Grafana\",
            \"version\": \"11.6.0\"
        },
        {
            \"type\": \"panel\",
            \"id\": \"timeseries\",
            \"name\": \"Time series\",
            \"version\": \"\"
        },
        {
            \"type\": \"datasource\",
            \"id\": \"yesoreyeram-infinity-datasource\",
            \"name\": \"Infinity\",
            \"version\": \"3.2.0\"
        }
    ],
    \"annotations\": {
        \"list\": [
            {
                \"builtIn\": 1,
                \"datasource\": {
                    \"type\": \"grafana\",
                    \"uid\": \"-- Grafana --\"
                },
                \"enable\": true,
                \"hide\": true,
                \"iconColor\": \"rgba(0, 211, 255, 1)\",
                \"name\": \"Annotations & Alerts\",
                \"type\": \"dashboard\"
            }
        ]
    },
    \"editable\": true,
    \"fiscalYearStartMonth\": 0,
    \"graphTooltip\": 0,
    \"id\": null,
    \"links\": [],
    \"panels\": [],
    \"refresh\": \"5s\",
    \"schemaVersion\": 41,
    \"tags\": [],
    \"templating\": {
        \"list\": []
    },
    \"time\": {
        \"from\": \"now-6h\",
        \"to\": \"now\"
    },
    \"timepicker\": {},
    \"timezone\": \"browser\",
    \"title\": \"Test\",
    \"version\": 2,
    \"weekStart\": \"\"
}
";
        $dashboard = json_decode($rawData, true);
        if($dashboard == NULL) {
            die("internal error dashboard\n");
        }

        $genPanels = [];
        $panelId = 1;
        foreach($this->getAllLayoutSubelements() as $p) {
            $p->setPanelId($panelId++);
            $genPanels[] = $p->generate();
        }
        $dashboard["panels"] = $genPanels;
        $dashboard["uid"] = $this->generateUID();

        return $dashboard;
    }

    private function generateUID() {
        $uidLen = 14;
        $uidChars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $charactersLength = strlen($uidChars);
        $randomString = '';
    
        for ($i = 0; $i < $uidLen; $i++) {
            $randomString .= $uidChars[random_int(0, $charactersLength - 1)];
        }
    
        return $randomString;
    }
}