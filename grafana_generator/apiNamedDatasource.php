<?php

require_once "datasource.php";

class APINamedDatasource extends Datasource {
    private $table;
    private $rows;
    private $globalDatasourcePart;
    private $nameRow;
    private $baseUrl;

    public function __construct($globalDatasourcePart, $table, $nameRow, array $rows, $baseUrl="namedSeries") {
        $this->table = $table;
        $this->rows = $rows;
        $this->globalDatasourcePart = $globalDatasourcePart;
        $this->nameRow = $nameRow;
        $this->baseUrl = $baseUrl;
    }

    public function generate() {
        $rawData = "
{
    \"targets\": [
        {
            \"columns\": [],
            \"filters\": [],
            \"format\": \"table\",
            \"global_query_id\": \"\",
            \"parser\": \"backend\",
            \"refId\": \"A\",
            \"root_selector\": \"\",
            \"source\": \"url\",
            \"type\": \"json\",
            \"url_options\": {
                \"headers\": [],
                \"method\": \"GET\",
                \"params\": [
                    {
                        \"key\": \"date_from\",
                        \"value\": \"\${__from:timestamp}\"
                    },
                    {
                        \"key\": \"date_to\",
                        \"value\": \"\${__to:timestamp}\"
                    }
                ]
            }
        }
    ],
    \"transformations\": [
        {
            \"id\": \"convertFieldType\",
            \"options\": {
                \"conversions\": [
                    {
                        \"destinationType\": \"number\",
                        \"targetField\": \"t\"
                    }
                ],
                \"fields\": {}
            }
        },
        {
            \"id\": \"convertFieldType\",
            \"options\": {
                \"conversions\": [
                    {
                        \"destinationType\": \"time\",
                        \"targetField\": \"t\"
                    }
                ],
                \"fields\": {}
            }
        }
    ]
}
";
        $tmp = json_decode($rawData, true);
        if($tmp == NULL) {
            die("internal error apiDatasource\n");
        }

        $tmp["targets"][0]["url"] = $this->baseUrl;
        $tmp["targets"][0]["url_options"]["params"][] = [
            "key" => "table",
            "value" => $this->table,
        ];

        $tmp["targets"][0]["url_options"]["params"][] = [
            "key" => "nameCol",
            "value" => $this->nameRow,
        ];

        for($i = 0; $i < count($this->rows); $i++) {
            $tmp["targets"][0]["url_options"]["params"][] = [
                "key" => "row[$i]",
                "value" => $this->rows[$i],
            ];

            $tmp["transformations"][0]["options"]["conversions"][] = [
                "destinationType" => "number",
                "targetField" => $this->rows[$i],
            ];
        }

        $tmp["transformations"] = array_merge($tmp["transformations"], $this->additionalTransformations);
        $tmp["datasource"] = $this->globalDatasourcePart;
        $tmp["targets"][0]["datasource"] = $this->globalDatasourcePart;
        return $tmp;
    }
}
