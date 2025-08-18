<?php

require_once "dashboard.php";

class Gauge extends DashboardPanel {
    public function __construct($title)
    {
        parent::__construct($title);

        $this->setSize(6, 8);
    }

    public function generate() {
        $rawData = "
    {
        \"datasource\": {
            \"type\": \"yesoreyeram-infinity-datasource\",
            \"uid\": \"\${DS_YESOREYERAM-INFINITY-DATASOURCE}\"
        },
        \"fieldConfig\": {
            \"defaults\": {
                \"color\": {
                    \"mode\": \"thresholds\"
                },
                \"mappings\": [],
                \"thresholds\": {
                    \"mode\": \"absolute\",
                    \"steps\": [
                        {
                            \"color\": \"green\"
                        },
                        {
                            \"color\": \"red\",
                            \"value\": 80
                        }
                    ]
                }
            },
            \"overrides\": []
        },
        \"options\": {
            \"minVizHeight\": 75,
            \"minVizWidth\": 75,
            \"orientation\": \"auto\",
            \"reduceOptions\": {
                \"calcs\": [
                    \"lastNotNull\"
                ],
                \"fields\": \"\",
                \"values\": false
            },
            \"showThresholdLabels\": false,
            \"showThresholdMarkers\": true,
            \"sizing\": \"auto\"
        },
        \"pluginVersion\": \"11.6.0\",
        \"targets\": [
            {
                \"columns\": [],
                \"filters\": [],
                \"format\": \"table\",
                \"global_query_id\": \"\",
                \"refId\": \"A\",
                \"root_selector\": \"\",
                \"source\": \"url\",
                \"type\": \"json\",
                \"url\": \"\",
                \"url_options\": {
                    \"data\": \"\",
                    \"method\": \"GET\"
                },
                \"datasource\": {
                    \"type\": \"yesoreyeram-infinity-datasource\",
                    \"uid\": \"\${DS_YESOREYERAM-INFINITY-DATASOURCE}\"
                }
            }
        ],
        \"type\": \"gauge\"
    }
    ";
        $tmp = json_decode($rawData, true);
        if($tmp == NULL) {
            die("internal error gauge\n");
        }
        return $this->addIdTitlePos($tmp);
    }
}
