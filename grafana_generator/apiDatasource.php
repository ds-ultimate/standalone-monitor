<?php

require_once "datasource.php";

class APIDatasource extends Datasource {
    private $baseUrl;
    private $table;
    private $rows;
    private $additionalTransformations;

    public function __construct($baseUrl, $table, array $rows) {
        $this->baseUrl = $baseUrl;
        $this->table = $table;
        $this->rows = $rows;
        $this->additionalTransformations = [];
    }

    public function addTransformationCalculate($field1, $operator, $field2, $newName) {
        $this->additionalTransformations[] = [
            "id" => "calculateField",
            "options" => [
                "alias" => $newName,
                "binary" => [
                    "left" => [
                        "matcher" => [
                            "id" => "byName",
                            "options" => $field1,
                        ],
                    ],
                    "operator" => $operator,
                    "right" => [
                        "matcher" => [
                            "id" => "byName",
                            "options" => $field2,
                        ],
                    ],
                ],
                "mode" => "binary",
            ],
        ];

        return $this;
    }

    public function addTransformationFilterByName($pattern) {
        $this->additionalTransformations[] = [
            "id" => "filterFieldsByName",
            "options" => [
                "include" => [
                    "pattern" => $pattern,
                ],
            ],
        ];

        return $this;
    }

    public function generate() {
        $rawData = "
{
    \"datasource\": {
        \"type\": \"yesoreyeram-infinity-datasource\",
        \"uid\": \"\${DS_YESOREYERAM-INFINITY-DATASOURCE}\"
    },
    \"targets\": [
        {
            \"columns\": [],
            \"datasource\": {
                \"type\": \"yesoreyeram-infinity-datasource\",
                \"uid\": \"\${DS_YESOREYERAM-INFINITY-DATASOURCE}\"
            },
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
        return $tmp;
    }
}
