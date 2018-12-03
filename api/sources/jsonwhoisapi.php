<?php 

require_once(realpath(dirname(__FILE__).'/../config/constants.php'));
require_once(realpath(dirname(__FILE__).'/../config/reusables.php'));

// JSONWHOIS API
// Refer : https://jsonwhoisapi.com/docs#auth
$requiredFields = array(
  'identifier',
  'Authorization'
);

validateRequest(array_merge(apache_request_headers(), $_POST), $requiredFields);

$reqParams = array(
  'identifier' => trim($_POST['identifier']),
);

$optionalParams = array(
  'Authorization' => 'Basic '.base64_encode(JSONWHOISAPI_API_ACC_NO.':'.JSONWHOISAPI_API_KEY)
);

$respArr = array();
$respArr = json_decode(curlIt(JSONWHOISAPI_API_URL, $reqParams, 'GET', $optionalParams));

echoResponse($respArr);

?>