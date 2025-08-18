<?php

require_once "dashboard.php";

class Gauge extends DashboardPanel {
    private Datasource $datasource;
    private $minMax;
    private $unit;

    public function __construct($title, $datasource)
    {
        parent::__construct($title);

        $this->datasource = $datasource;
        $this->minMax = null;
        $this->unit = null;
        $this->setSize(6, 8);
    }

    public function setMinMax($min, $max) {
        $this->minMax = [(int) $min, (int) $max];
        return $this;
    }

    public function setUnit($newUnit) {
        $this->unit = $newUnit;
        return $this;
    }

    public function generate() {
        $rawData = "
    {
        \"fieldConfig\": {
            \"defaults\": {
                \"color\": {
                    \"mode\": \"thresholds\"
                },
                \"mappings\": [],
                \"thresholds\": {
                    \"mode\": \"percentage\",
                    \"steps\": [
                        {
                            \"color\": \"green\"
                        },
                        {
                            \"color\": \"yellow\",
                            \"value\": 50
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
        \"type\": \"gauge\"
    }
    ";
        $tmp = json_decode($rawData, true);
        if($tmp == NULL) {
            die("internal error gauge\n");
        }

        $tmp = array_merge($tmp, $this->datasource->generate());
        if($this->minMax !== null) {
            $tmp["fieldConfig"]["defaults"]["min"] = $this->minMax[0];
            $tmp["fieldConfig"]["defaults"]["max"] = $this->minMax[1];
        }
        if($this->unit !== null) {
            $tmp["fieldConfig"]["defaults"]["unit"] = $this->unit;
        }

        return $this->addIdTitlePos($tmp);
    }
}
