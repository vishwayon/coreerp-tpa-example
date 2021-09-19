<?php

/**
 * @link http://www.vishwayon.com/
 * @copyright Copyright (c) 2021 Vishwayon Software Pvt Ltd
 * @license MIT
 */
require '../vendor/autoload.php';

// Never Pass the following variables to the client in HTML or JS. Always retain them securely on the server
$auth_key = '';          // This is the Authentication ID assigned to your application
$auth_secret = '';      // This is the Authentication Secret assigned to your application
$user_secret = '';      // User needs to generate this secret from his User Profile

// Application Paramertes
$user_name = '';        // The logon user_name in coreERP
$coreErpUrl = 'http://172.17.0.3/coreerp/index.php';      // coreERP published site address

// Document information 
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
    
    $requestPrint = new \GuzzleHttp\Psr7\Request('GET', '?r=app/get-print' . $qStr);
    $responsePrint = $client->send($requestPrint);
    $resultPrint = json_decode($responsePrint->getBody(), true);
    
    $rpt = new stdClass();
    if ($resultPrint['status'] == 'OK') {
        $rpt->status = 'OK';
        $rpt->msg = 'Success';
        $fPath = str_replace('index.php', $resultPrint['rpt'], $coreErpUrl);
        $rpt->filePath = $fPath;
    } else {
        $rpt->status = 'ERROR';
        $rpt->msg = $resultPrint['msg'];
        $rpt->filePath = '';
    }
    echo json_encode($rpt) . PHP_EOL;
} else {
    echo json_encode($result) . PHP_EOL;
}