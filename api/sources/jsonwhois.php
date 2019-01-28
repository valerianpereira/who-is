<?php

// JSONWHOIS API
// Refer : https://jsonwhois.io
$requiredFields = array(
    'domain | required_if:r,whois/availability',
    'r', // whois, ip, availability
    'ip_address | required_if:r,ip',
    'Authorization'
);

validateRequest(array_merge(apache_request_headers(), $_POST), $requiredFields);

$reqParams = array(
    'key' => JSONWHOIS_API_KEY
);

if ($_POST['r'] == 'ip') {
    $reqParams['ip_address'] = trim($_POST['ip_address']);
    $API_URL = JSONWHOIS_API_IP_URL;
} else {
    $reqParams['domain'] = trim($_POST['domain']);
    $API_URL = ($_POST['r'] == 'availability') ? JSONWHOIS_API_AVAIL_URL : JSONWHOIS_API_WHOIS_URL;
}

$respArr = array();
$respArr = json_decode(curlIt($API_URL, $reqParams, 'GET'), true);

echoResponse(formatResponse($respArr, 'JsonWhoIs'));

// Response if not found
// Array
// (
//     [result] => Array
//         (
//             [name] => somemaindomain.in
//             [created] => 
//             [changed] => 
//             [expires] => 
//             [dnssec] => 
//             [registered] => 
//             [status] => 
//             [nameservers] => 
//             [contacts] => Array
//                 (
//                 )

//             [registrar] => 
//         )

// )

// Response If Found
// Array
// (
//     [result] => Array
//         (
//             [name] => webgeeks.in
//             [created] => 2013-01-19 01:03:12
//             [changed] => 2018-10-22 05:36:38
//             [expires] => 2020-01-19 01:03:12
//             [dnssec] => unsigned
//             [registered] => 1
//             [status] => clientTransferProhibited
//             [nameservers] => Array
//                 (
//                     [0] => NS1.BH-61.WEBHOSTBOX.NET
//                     [1] => NS2.BH-61.WEBHOSTBOX.NET
//                 )

//             [contacts] => Array
//                 (
//                     [owner] => Array
//                         (
//                             [0] => Array
//                                 (
//                                     [handle] => 
//                                     [type] => 
//                                     [name] => 
//                                     [organization] => Freelance Web Solution
//                                     [email] => 
//                                     [address] => 
//                                     [zipcode] => 
//                                     [city] => 
//                                     [state] => Maharashtra
//                                     [country] => IN
//                                     [phone] => 
//                                     [fax] => 
//                                     [created] => 
//                                     [changed] => 
//                                 )

//                         )

//                 )

//             [registrar] => Array
//                 (
//                     [id] => 1291
//                     [name] => Crazy Domains FZ-LLC
//                     [email] => 
//                     [url] => http://policy.secureapi.eu
//                 )

//         )

// )

?>