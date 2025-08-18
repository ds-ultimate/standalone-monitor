<?php

abstract class Datasource {
    protected $additionalTransformations = [];
    public abstract function generate();

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

    public function addTransformationFilterByPattern($pattern) {
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

    public function addTransformationFilterByName($allowedNames) {
        $this->additionalTransformations[] = [
            "id" => "filterFieldsByName",
            "options" => [
                "include" => [
                    "names" => $allowedNames,
                ],
            ],
        ];

        return $this;
    }

    public function addTransformationSortByName($newOrder) {
        $indexNameArray = [];

        foreach($newOrder as $idx => $name) {
            $indexNameArray[$name] = $idx;
        }

        $this->additionalTransformations[] = [
            "id" => "organize",
            "options" => [
                "excludeByName" => [],
                "includeByName" => [],
                "indexByName" => $indexNameArray,
                "renameByName" => [],
            ],
        ];

        return $this;
    }
}
