<?php

require_once "dashboard.php";

class Row extends DashboardPanel {
    public function __construct($title)
    {
        parent::__construct($title);

        $this->setSize(24, 1);
    }

    public function generate() {
        $rawData = "
{
    \"collapsed\": false,
    \"panels\": [],
    \"type\": \"row\"
}
";
        $tmp = json_decode($rawData, true);
        if($tmp == NULL) {
            die("internal error row\n");
        }
        return $this->addIdTitlePos($tmp);
    }
}