<?php

require('cmdbTokenReq.php');
$printTerm = $_GET['term'];
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_FOLLOWLOCATION, true,
    CURLOPT_SSL_VERIFYPEER, false,
    CURLOPT_PORT => "8443",
    CURLOPT_URL => "$urlQuery",
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_HEADER => 0,
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 4,
    CURLOPT_CONNECTTIMEOUT => 4,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "{\r\n              \"nodes\": \r\n              [\r\n                     {\r\n                     \"type\": \"node\",\r\n                     \"queryIdentifier\": \"server\",\r\n                     \"visible\": true,\r\n                     \"includeSubtypes\": true,\r\n                     \"layout\": [\"name\"],\r\n                     \"attributeConditions\": \r\n                           [{\r\n                                  \"attribute\": \"name\",\r\n                                  \"operator\": \"equals\",\r\n                                \"value\": \"$printTerm\"\r\n                                  }],\r\n                     \"linkConditions\": \r\n                           [{\r\n                                         \"linkIdentifier\": \"OS_Support\",\r\n                                         \"minCardinality\": \"0\",\r\n                                         \"maxCardinality\": \"*\"\r\n                                  },\r\n                                  {\r\n                                         \"linkIdentifier\": \"OS_Support_Delegate\",\r\n                                         \"minCardinality\": \"0\",\r\n                                         \"maxCardinality\": \"*\"\r\n                                  },\r\n                                 {\r\n                                         \"linkIdentifier\": \"Group_Support\",\r\n                                         \"minCardinality\": \"0\",\r\n                                         \"maxCardinality\": \"*\"\r\n                                  }]\r\n                     },\r\n                     {\r\n                     \"type\": \"person\",\r\n                     \"queryIdentifier\": \"Support\",\r\n                     \"visible\": true,\r\n                     \"includeSubtypes\": true,\r\n                     \"layout\": [\"display_label\"],\r\n                     \"linkConditions\":\r\n                           [{\r\n                                         \"linkIdentifier\": \"OS_Support\",\r\n                                         \"minCardinality\": \"1\",\r\n                                         \"maxCardinality\": \"*\"\r\n                                  }]\r\n                    },\r\n                     {\r\n                     \"type\": \"person\",\r\n                     \"queryIdentifier\": \"Support_Delegate\",\r\n                     \"visible\": true,\r\n                     \"includeSubtypes\": true,\r\n                     \"layout\": [\"display_label\"],               \r\n                    \"linkConditions\":\r\n                           [\r\n                               {\r\n                                         \"linkIdentifier\": \"OS_Support_Delegate\",\r\n                                         \"minCardinality\": \"1\",\r\n                                         \"maxCardinality\": \"*\"\r\n                                  }\r\n                            ]\r\n                     },\r\n                    {  \r\n                    \"type\": \"rbru_itsm_support_group\",\r\n                    \"queryIdentifier\": \"Support_Group\",\r\n                    \"visible\": true,\r\n                    \"includeSubtypes\": true,\r\n                    \"layout\": [\"display_label\"],               \r\n                    \"linkConditions\":\r\n                        [\r\n                                {\r\n                                        \"linkIdentifier\": \"Group_Support\",\r\n                                        \"minCardinality\": \"1\",\r\n                                        \"maxCardinality\": \"*\"\r\n                                }\r\n                        ]\r\n                    }      \r\n              ],\r\n              \"relations\": \r\n                     [\r\n                           { \r\n                                  \"queryIdentifier\": \"OS_Support\",\r\n                                    \"type\": \"rbru_os_support\",\r\n                                  \"visible\": true,\r\n                                  \"includeSubtypes\": true,\r\n                                    \"from\": \"Support\",\r\n                                  \"to\": \"server\"\r\n                           },\r\n                           { \r\n                                  \"queryIdentifier\": \"OS_Support_Delegate\",\r\n                                  \"type\":       \"rbru_os_support_delegate\",                \r\n                                  \"visible\": true,\r\n                                  \"includeSubtypes\": true,\r\n                                    \"from\": \"Support_Delegate\",\r\n                                  \"to\": \"server\"\r\n                           },\r\n                            { \r\n                                  \"queryIdentifier\": \"Group_Support\",\r\n                                  \"type\":       \"rbru_assignment_link\",                 \r\n                                  \"visible\": true,\r\n                                  \"includeSubtypes\": true,\r\n                                    \"from\": \"Support_Group\",\r\n                                  \"to\": \"server\"\r\n                           }\r\n                     ]\r\n       }",
    CURLOPT_HTTPHEADER => array(
        "Accept: */*",
        "Authorization: Bearer $token",
        "Cache-Control: no-cache",
        "Connection: keep-alive",
        "Content-Type: application/json",
        "Host: $serverName",
        "User-Agent: Routing Search/beta2.0",
        "accept-encoding: gzip, deflate",
        "cache-control: no-cache",
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
    echo "<div class='notFound'>cURL Error #:" . $err . "</div>";
} else {
    $body = json_decode($response, true); //Decode Json by casting it to the array form
}

print "<div class='cmdbResult'>";

    if(is_array($body)) {
        $bodys = array_filter($body);
    } else {
        $bodys = array();
    }

    if (!empty($bodys)) {
        foreach($bodys['cis'] as $cmdbResult) {
            print $cmdbResult['properties']['name'];
            print $cmdbResult['properties']['display_label'];
            print "<hr>";
        }
    } else {
        print "<div class='notFound'>Not Found! Попробуйте изменить запрос. Искать можно только сервер. Найти можно только по-полному названию. Пример: s-msk-p-appsd </div>";
    }

print "</div>";