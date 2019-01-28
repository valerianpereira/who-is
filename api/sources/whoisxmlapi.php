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
