<?php

/**
 * @link http://www.vishwayon.com/
 * @copyright Copyright (c) 2021 Vishwayon Software Pvt Ltd
 * @license MIT
 */
require '../vendor/autoload.php';

// Never Pass the following variables to the client in HTML or JS. Always retain them securely on the server
$auth_key = '4ae9254bbf211d6ca434af746a4f9026';          // This is the Authentication ID assigned to your application
$auth_secret = 'e1891618a18e2b2a06e99c75cbaa0e74';      // This is the Authentication Secret assigned to your application
$user_secret = '2771179fd18a577cc69bd57eac4d7c0e';      // User needs to generate this secret from his User Profile
// Application Paramertes
$user_name = 'Support_user';        // The logon user_name in coreERP
$coreErpUrl = 'http://172.17.0.3/coreerp/index.php';      // coreERP published site address
//
$doc_type = filter_input(INPUT_GET, 'doc-type', FILTER_SANITIZE_STRING);
$doc_id = filter_input(INPUT_GET, 'doc-id', FILTER_SANITIZE_STRING);

// Create a Ghuzzle Client instance and call authentication
$client = new GuzzleHttp\Client([
    // Base URI is used with relative requests
    'base_uri' => $coreErpUrl,
    // You can set any number of default request options.
    'timeout' => 2.0,
        ]);

// Prepare Authorization header
$ah = base64_encode($auth_key . ':' . $auth_secret);
// Prepare Request with headers
$request = new \GuzzleHttp\Psr7\Request('GET', '?r=auth/key-login&user=' . $user_name, [
    'Authorization' => $ah,
    'Usersecret' => base64_encode($user_secret)
        ]);
// Process Response
$response = $client->send($request);
$result = json_decode($response->getBody(), true);
if ($result['status'] == 'OK') {

    $qStr = '&auth-token=' . $result['auth-token'] . '&doc-id=' . $doc_id . '&doc-type=' . $doc_type;
//    $requestPrint = new \GuzzleHttp\Psr7\Request('GET', '?r=app/get-print' . $qStr, ['headers' => ['core-sessionid' => $result['core-sessionid']]]);
    $requestPrint = new \GuzzleHttp\Psr7\Request('GET', '?r=app/get-print' . $qStr);
    try {
        $responsePrint = $client->send($requestPrint);
        $resultPrint = json_decode($responsePrint->getBody());
        echo $responsePrint->getBody();
        
//        $responsePrint = $client->get('?r=app/get-print', [
////            'headers' => [
////                'Accept' => 'application/json',
////                'Authorization' => $ah,
////                'Usersecret' => base64_encode($user_secret),
////                'core-sessionid' => $result['core-sessionid']
////            ],
//            'query' => [
//                'auth-token' => $result['auth-token'], 'doc-id' => $doc_id, 'doc-type' => $doc_type
//            ]
//        ]);
////        $responsePrint = $client->send($requestPrint);
//        $resultPrint = json_decode($responsePrint->getBody(), true);
//        echo json_encode(['sts' => 'print response', 'resp' => json_encode($resultPrint)]) . PHP_EOL;
//        $rpt = new stdClass();
//        $rpt->status = 'OK';
//        $rpt->url = $coreErpUrl;
//        $rpt->filePath = $resultPrint['ReportRenderedPath'];
//        echo json_encode($rpt) . PHP_EOL;
    } catch (Exception $ex) {
        echo 'exception cought' . PHP_EOL . json_encode(json_decode($ex->getResponse()->getBody()->getContents(), true)) . PHP_EOL;
    }
} else {
    echo json_encode($result) . PHP_EOL;
}

//$result = json_decode($response->getBody());
//if ($result->status == 'OK') {
//    $result->url = $coreErpUrl;
//    $result->route = 'app/get-print';
//}
//echo json_encode($result) . PHP_EOL;