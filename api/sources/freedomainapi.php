<?php 

require_once(realpath(dirname(__FILE__) . '/../config/constants.php'));
require_once(realpath(dirname(__FILE__) . '/../config/reusables.php'));

// freedomainapi.com (1 Request per minute)
// Refer : https://freedomainapi.com/free-domain-availability-api.html
// Refer : https://freedomainapi.com/free-whois-api.html
$requiredFields = array(
    'domain',
    'r',
    'Authorization'
);

validateRequest(array_merge(apache_request_headers(), $_POST), $requiredFields);

$reqParams = array(
    'domain' => trim($_POST['domain']),
    'r' => trim($_POST['r']), //whois, taken
    'apikey' => FREEDOMAIN_API_KEY
);

$respArr = array();
$respArr = json_decode(curlIt(FREEDOMAIN_API_URL, $reqParams, 'GET'), true);

echoResponse(formatResponse($respArr, 'FreeDomainApi'));

// Response Paid Service
// {
//     "status": 1,
//     "error_description": "Attention! Please make a one time payment for lifetime access. For more details please authorize at freedomainapi.com."
// }

?>