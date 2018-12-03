<?php // Require CONSTANTS

// Validating Request
function validateRequest($variables = array(), $requiredFields = array()) {

  $respArr = array();
  $respFlag = false;
  $respCode = 204;

  // Validating Required Variables
  if(!empty($requiredFields) && !empty($variables)) : 
    foreach($requiredFields as $f) :

      if(strpos($f,'|')) { // Validation Exist
        $tempArr = explode('|', $f);
        if(!empty($tempArr) && count($tempArr) > 1) {
          $f = !empty($tempArr[0]) ? $tempArr[0] : end($tempArr);

          
        }
      }

      if(isset($variables[$f]) && !empty($variables[$f])) {
        if($f == 'Authorization') { // Validating Authorization
          $tempArr = explode(' ', $variables[$f]);
          $token = end($tempArr);
          if (!(!empty($token) && defined('VERIFY_TOKEN') && $token === VERIFY_TOKEN)) {
            // TODO: Log it for Admins
            $respFlag = false;
            $respCode = 401;
            $respArr['message'] = 'Mind your request!! You are unauthorized to do this.';
            break;
          }
        } 
        $respFlag = true;
        $respCode = 200;
      } else {
        $respFlag = false;
        $respArr['message'] = 'Fella !! You might forgot something to bring here.';
        $respCode = 206;
        break;
      }
    endforeach;
  else :
    $respCode = 204;
    $respArr['message'] = 'Fella !! You might forgot something to bring here.';
    $respFlag = false;
  endif;

  if($respFlag) {
    return true;
  } else {
    $respArr['status'] = 'error';
    $respArr['data'] = '';
  }
  echoResponse($respArr, $respCode);

}

// Kal function
function curlIt($url, $params, $method = "POST", $optionalParams = array()) {

  $ch = curl_init();
  
  if(strtoupper($method) == "GET") {
    $url.= (substr($url, -1) != '/') ? '/' : ''; // Putting a Slash if Missing
    curl_setopt($ch, CURLOPT_URL, $url.'?'.http_build_query($params));
  } else {
    curl_setopt($ch, CURLOPT_URL, $url);
  }

  curl_setopt($ch, CURLOPT_HEADER, false);

  if(!empty($optionalParams['Authorization'])) {
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:". $optionalParams['Authorization']));
  }

  if(!empty($optionalParams['userPwd'])) {
    curl_setopt($ch, CURLOPT_USERPWD, $optionalParams['userPwd']);
  }

  if(!empty($optionalParams['Content-Type'])) {
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:". $optionalParams['Content-Type']));
  }

  if(strtoupper($method) == "GET") {
    curl_setopt($ch, CURLOPT_HTTPGET, 1);
  } else if(strtoupper($method) == "POST") {
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
  }

  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

  $response = curl_exec($ch);
  $err = curl_error($ch);

  curl_close($ch);

  if ($err) {
    // TODO: Log Curl Errors, Instead Showing it
    return "cURL Error #:" . $err;
  } else {
    return $response;
  }

}

function echoResponse($responseArr,$code = 200) {
  // Clearing Old Headers
  header_remove();

  // Setting the Actual Header
  http_response_code($code);

  // Forcing Cache
  header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");

  // JSONning Response
  header('Content-Type: application/json');

  // Status Code Definitions
  $statusCodes = array(
      200 => '200 OK',
      201 => '201 Object Created',
      204 => '204 No Content',
      206 => '206 Partial Content',
      400 => '400 Bad Request',
      401 => '401 Unauthorized',
      403 => '403 Forbidden',
      404 => '404 Not Found',
      422 => '422 Unprocessable Entity',
      500 => '500 Internal Server Error',
      503 => '503 Service Unavailable'
  );

  // Setting Status Code
  header('Status: '.$statusCodes[$code]);

  // Echoing JSON Output
  echo json_encode($responseArr);

  // Die
  die();
}