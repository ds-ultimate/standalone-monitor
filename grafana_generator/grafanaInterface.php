<?php


function getGlobalDatasource() {
    global $GRAFANA_HOST;
    $url = "http://$GRAFANA_HOST/api/datasources";
    $response = performGrafanaApiRequest($url);

    $rawData = json_decode($response, true);
    $found_one = null;
    foreach($rawData as $datasourceEntry) {
        if($datasourceEntry["type"] == "yesoreyeram-infinity-datasource") {
            if($found_one !== null) {
                echo "Multiple yesoreyeram-infinity-datasource datasources found. Only 1 supported\n";
                die();
            }
            $found_one = $datasourceEntry;
        }
    }

    if($found_one == null) {
        echo "No datasource found. Please configure one...\n";
        die();
    }

    return [
        "type" => "yesoreyeram-infinity-datasource",
        "uid" => "{$found_one["uid"]}",
    ];
}


function performImport($rawDashboard) {
    global $GRAFANA_HOST, $GRAFANA_HOST_EXT;

    $importData = [
        "dashboard" => $rawDashboard,
        "overwrite" => true,
        "folderId" => 0,
        "message" => "Imported via API",
    ];
    $dashboardJson = json_encode($importData);

    $url = "http://$GRAFANA_HOST/api/dashboards/db";
    $response = json_decode(performGrafanaApiRequest($url, $dashboardJson), true);

    echo "Import successful. Available at:\n$GRAFANA_HOST_EXT{$response["url"]}\n";
}


function performGrafanaApiRequest($apiUrl, $postPayload=null) {
    global $GRAFANA_USERNAME, $GRAFANA_PASSWORD;

    $ch = curl_init($apiUrl);

    // Use Basic Auth
    curl_setopt($ch, CURLOPT_USERPWD, "$GRAFANA_USERNAME:$GRAFANA_PASSWORD");

    if($postPayload !== null) {
        // POST request with JSON payload
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json"
        ]);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postPayload);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute request
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo "cURL error: " . curl_error($ch) . "\n";
        die();
    }

    curl_close($ch);

    return $response;
}
