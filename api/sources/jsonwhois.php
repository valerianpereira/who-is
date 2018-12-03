<?php 

require_once(realpath(dirname(__FILE__).'/../config/constants.php'));
require_once(realpath(dirname(__FILE__).'/../config/reusables.php'));

// JSONWHOIS API
// Refer : https://jsonwhoisapi.com/docs#auth
$requiredFields = array(
  'domain | required_if:r,whois/availability',
  'r', // whois, ip, availability
  'ip_address | required_if:r,ip',
  'Authorization'
);

validateRequest(array_merge(apache_request_headers(), $_POST), $requiredFields);

if($_POST['r'] == 'ip') {
  $reqParams['ip_address'] = trim($_POST['ip_address']);
} else {
  $reqParams['domain'] = trim($_POST['domain']);
}
$reqParams = array(
  'key' => JSONWHOIS_API_KEY
);

$respArr = array();
$respArr = json_decode(curlIt(JSONWHOIS_API_URL, $reqParams, 'GET'));

echoResponse($respArr);

?>