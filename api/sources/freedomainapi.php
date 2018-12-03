<?php 

require_once(realpath(dirname(__FILE__).'/../config/constants.php'));
require_once(realpath(dirname(__FILE__).'/../config/reusables.php'));

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
$respArr = json_decode(curlIt(FREEDOMAIN_API_URL, $reqParams, 'GET'));

echoResponse($respArr);





// $curl = curl_init();

// curl_setopt_array($curl, array(
//   CURLOPT_URL => "http://api.freedomainapi.com/?domain=freedomainapi.com&r=whois&apikey=9fb7b975cfea208473c41191beb0c139%0A",
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => "",
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 30,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => "GET",
//   CURLOPT_POSTFIELDS => "",
//   CURLOPT_HTTPHEADER => array(
//     "Postman-Token: df0d860f-bf54-46ba-8b2b-b8945479bafc",
//     "cache-control: no-cache"
//   ),
// ));

// $response = curl_exec($curl);
// $err = curl_error($curl);

// curl_close($curl);

// if ($err) {
//   echo "cURL Error #:" . $err;
// } else {
//   echo $response;
// }


?>