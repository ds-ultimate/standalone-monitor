<?php

require_once "dashboard.php";
require_once "datasource.php";

class Timeseries extends DashboardPanel {
    private Datasource $datasource;
    private $unit;
    private $graphType = "line";
    private $showPoints = "auto";
    private $stackingMode = "none";
    private $tooltipMode = "single";
    private $lineWidth = 1;
    private $fillOpacity = 0;

    public function __construct($title, Datasource $datasource)
    {
        parent::__construct($title);

        $this->datasource = $datasource;
        $this->unit = null;
        $this->setSize(18, 8);
    }

    public function setUnit($newUnit) {
        $this->unit = $newUnit;
        return $this;
    }

    public function setGraphType($newGraphType) {
        $this->graphType = $newGraphType;
        return $this;
    }

    public function setLineWidth($lineWidth) {
        $this->lineWidth = $lineWidth;
        return $this;
    }

    public function setFillOpacity($fillOpacity) {
        $this->fillOpacity = $fillOpacity;
        return $this;
    }

    public function setDisplayPoints($newVal) {
        $this->showPoints = $newVal;
        return $this;
    }

    public function setStackingMode($newMode) {
        $this->stackingMode = $newMode;
        return $this;
    }

    public function setTooltipMode($newMode) {
        $this->tooltipMode = $newMode;
        return $this;
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
                \"barWidthFactor\": 1,
                \"drawStyle\": \"{$this->graphType}\",
                \"fillOpacity\": {$this->fillOpacity},
                \"gradientMode\": \"none\",
                \"hideFrom\": {
                    \"legend\": false,
                    \"tooltip\": false,
                    \"viz\": false
                },
                \"insertNulls\": false,
                \"lineInterpolation\": \"linear\",
                \"lineWidth\": {$this->lineWidth},
                \"pointSize\": 5,
                \"scaleDistribution\": {
                    \"type\": \"linear\"
                },
                \"showPoints\": \"{$this->showPoints}\",
                \"spanNulls\": false,
                \"stacking\": {
                    \"group\": \"A\",
                    \"mode\": \"{$this->stackingMode}\"
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
            \"mode\": \"{$this->tooltipMode}\",
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

        if($this->unit !== null) {
            $tmp["fieldConfig"]["defaults"]["unit"] = $this->unit;
        }

        return $this->addIdTitlePos($tmp);
    }
}