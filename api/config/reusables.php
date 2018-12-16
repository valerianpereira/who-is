<?php // Require CONSTANTS

// Validating Request
function validateRequest($variables = array(), $requiredFields = array()) {

  $respArr = array();
  $respFlag = false;
  $respCode = 204;

  // Validating Required Variables
  if(!empty($requiredFields) && !empty($variables)) : 
    foreach($requiredFields as $f) :

      // Check if more validations exists
      $tempArr = strpos($f,'|') ? explode('|', $f) : array();
      if(!empty($tempArr) && count($tempArr) > 1) :
        // Setting a exact value of key
        $f = !empty($tempArr[0]) ? trim($tempArr[0]) : trim(end($tempArr));
        
        // Dynamic Validations
        $validationArr = (!empty($tempArr[1]) && strpos($tempArr[1],':')) ? explode(':',$tempArr[1]) : array();
        if(!empty($validationArr) && count($validationArr) > 1) :
          // Required If Validation
          if(trim($validationArr[0]) == 'required_if') { 
            $kv = (!empty($validationArr[1]) && strpos($validationArr[1],',')) ? explode(',',$validationArr[1]) : array();
            if(!empty($kv) && count($kv) > 1 && !empty($kv[0])) {
              $k = trim($kv[0]);
              $v = (!empty($kv[1]) && strpos($kv[1],'/')) ? explode('/',$kv[1]) : $kv[1];
              
              if(isset($variables[$k]) && !empty($variables[$k])) {
                // Does it have multiple values ? divided by /
                if(is_array($v) && !in_array($variables[$k],$v)) {
                  continue; // Skip validation
                }
                if(!is_array($v) && $variables[$k] != $v) {
                    continue; // Skip validation
                }
              }
            }
          } // Required If Validation

        endif;
      endif;
      
      if(isset($variables[$f]) && !empty($variables[$f])) {
        if($f == 'Authorization') { // Validating Authorization Token
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

// Response JSONified with Headers
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