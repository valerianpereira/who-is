<?php 

require_once(realpath(dirname(__FILE__) . '/../config/constants.php'));
require_once(realpath(dirname(__FILE__) . '/../config/reusables.php'));

// JSONWHOIS API
// Refer : https://jsonwhoisapi.com/docs#auth
$requiredFields = array(
    'domain',
    'Authorization'
);

validateRequest(array_merge(apache_request_headers(), $_POST), $requiredFields);

$reqParams = array(
    'identifier' => trim($_POST['domain']),
);

$optionalParams = array(
    'Authorization' => 'Basic ' . base64_encode(JSONWHOISAPI_API_ACC_NO . ':' . JSONWHOISAPI_API_KEY)
);

$respArr = array();
$respArr = json_decode(curlIt(JSONWHOISAPI_API_URL, $reqParams, 'GET', $optionalParams), true);

echoResponse(formatResponse($respArr, 'JsonWhoIsApi'));

// Response IF Not Found
// Array
// (
//     [name] => somemaindomain.in
//     [created] => 
//     [changed] => 
//     [expires] => 
//     [dnssec] => 
//     [registered] => 
//     [status] => 
//     [nameservers] => Array
//         (
//         )

//     [contacts] => Array
//         (
//             [owner] => Array
//                 (
//                 )

//             [admin] => Array
//                 (
//                 )

//             [tech] => Array
//                 (
//                 )

//         )

//     [registrar] => Array
//         (
//             [id] => 
//             [name] => 
//             [email] => 
//             [url] => 
//             [phone] => 
//         )

//     [throttled] => 
// )

// Response IF Found
// Array
// (
//     [name] => webgeeks.in
//     [created] => 2013-01-19 01:03:12
//     [changed] => 2018-10-22 05:36:38
//     [expires] => 2020-01-19 01:03:12
//     [dnssec] => 
//     [registered] => 1
//     [status] => registered
//     [nameservers] => Array
//         (
//             [0] => ns1.bh-61.webhostbox.net
//             [1] => ns2.bh-61.webhostbox.net
//         )

//     [contacts] => Array
//         (
//             [owner] => Array
//                 (
//                 )

//             [admin] => Array
//                 (
//                 )

//             [tech] => Array
//                 (
//                 )

//         )

//     [registrar] => Array
//         (
//             [id] => 1291
//             [name] => Crazy Domains FZ-LLC
//             [email] => 
//             [url] => http://policy.secureapi.eu
//             [phone] => 
//         )

//     [throttled] => 
// )

?>