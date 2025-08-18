<?php

require_once "dashboard.php";
require_once "datasource.php";

class Timeseries extends DashboardPanel {
    private Datasource $datasource;

    public function __construct($title, Datasource $datasource)
    {
        parent::__construct($title);

        $this->datasource = $datasource;
        $this->setSize(18, 8);
    }

    public function generate() {
        $rawData = "
{
    \"fieldConfig\": {
        \"defaults\": {
            \"color\": {
                \"mode\": \"palette-classic\"
            },
            \"custom\": {
                \"axisBorderShow\": false,
                \"axisCenteredZero\": false,
                \"axisColorMode\": \"text\",
                \"axisLabel\": \"\",
                \"axisPlacement\": \"auto\",
                \"barAlignment\": 0,
                \"barWidthFactor\": 0.6,
                \"drawStyle\": \"line\",
                \"fillOpacity\": 0,
                \"gradientMode\": \"none\",
                \"hideFrom\": {
                    \"legend\": false,
                    \"tooltip\": false,
                    \"viz\": false
                },
                \"insertNulls\": false,
                \"lineInterpolation\": \"linear\",
                \"lineWidth\": 1,
                \"pointSize\": 5,
                \"scaleDistribution\": {
                    \"type\": \"linear\"
                },
                \"showPoints\": \"auto\",
                \"spanNulls\": false,
                \"stacking\": {
                    \"group\": \"A\",
                    \"mode\": \"none\"
                },
                \"thresholdsStyle\": {
                    \"mode\": \"off\"
                }
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
        \"legend\": {
            \"calcs\": [],
            \"displayMode\": \"list\",
            \"placement\": \"bottom\",
            \"showLegend\": true
        },
        \"tooltip\": {
            \"hideZeros\": false,
            \"mode\": \"single\",
            \"sort\": \"none\"
        }
    },
    \"pluginVersion\": \"11.6.0\",
    \"type\": \"timeseries\"
}
";
        $tmp = json_decode($rawData, true);
        if($tmp == NULL) {
            die("internal error timeseries\n");
        }
        $tmp = array_merge($tmp, $this->datasource->generate());
        return $this->addIdTitlePos($tmp);
    }
}