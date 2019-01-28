<?php 

// WHOISXMLAPI API
// Refer : https://whoisapi.whoisxmlapi.com/docs
$requiredFields = array(
    'domain',
    'Authorization'
);

validateRequest(array_merge(apache_request_headers(), $_POST), $requiredFields);

// Incase if you want to enquire for domain availability just add one more key 
// 'da' => 2
$reqParams = array(
    'apiKey' => WHOISXML_API_KEY,
    'outputFormat' => 'JSON',
    'thinWhois' => 1,
    'domainName' => trim($_POST['domain'])
);

$respArr = array();
$respArr = json_decode(curlIt(WHOISXML_API_URL, $reqParams, 'GET'), true);

echoResponse(formatResponse($respArr, 'WhoIsXml'));

// Response if not found
// Array
// (
//     [WhoisRecord] => Array
//         (
//             [domainName] => somemaindomain.in
//             [parseCode] => 0
//             [audit] => Array
//                 (
//                     [createdDate] => 2019-01-18 02:41:05.000 UTC
//                     [updatedDate] => 2019-01-18 02:41:05.000 UTC
//                 )

//             [dataError] => MISSING_WHOIS_DATA
//             [registryData] => Array
//                 (
//                     [domainName] => somemaindomain.in
//                     [rawText] => NOT FOUND

// Response if Found
// Array
// (
//     [WhoisRecord] => Array
//         (
//             [domainName] => webgeeks.in
//             [parseCode] => 8
//             [audit] => Array
//                 (
//                     [createdDate] => 2019-01-18 02:39:39.000 UTC
//                     [updatedDate] => 2019-01-18 02:39:39.000 UTC
//                 )

//             [registrarName] => Crazy Domains FZ-LLC
//             [registryData] => Array
//                 (
//                     [createdDate] => 2013-01-19T01:03:12Z
//                     [updatedDate] => 2018-10-22T05:36:38Z
//                     [expiresDate] => 2020-01-19T01:03:12Z
//                     [registrant] => Array
//                         (
//                             [organization] => Freelance Web Solution
//                             [state] => Maharashtra
//                             [country] => INDIA
//                             [countryCode] => IN
//                             [rawText] => Registrant Country: IN
//                         )

//                     [domainName] => webgeeks.in
//                     [nameServers] => Array
//                         (
//                             [rawText] => NS1.BH-61.WEBHOSTBOX.NET
// NS2.BH-61.WEBHOSTBOX.NET

//                             [hostNames] => Array
//                                 (
//                                     [0] => NS1.BH-61.WEBHOSTBOX.NET
//                                     [1] => NS2.BH-61.WEBHOSTBOX.NET
//                                 )

//                             [ips] => Array
//                                 (
//                                 )

//                         )

//                     [status] => clientTransferProhibited
?>