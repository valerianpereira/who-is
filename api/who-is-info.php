<?php

header('Content-Type: application/json;charset=utf-8');

$output = array();
$output['error']['message'] = 'Oops! The domain ' . $_GET['domain'] . ' is not registered';

if ($_GET['domain'] != '') {

    $sampleData = fnCurl('https://api.jsonwhois.io/whois/domain?key=KlJ6r4DAraYyQ5m7fTKHp9WjBrU6A7OP&domain=' . $_GET['domain'], '', 'GET');
    
    /*$sampleData = '{
  "result": {
    "name": "hi5solutions.in",
    "created": "2018-07-21 13:07:41",
    "changed": "2018-11-28 07:53:59",
    "expires": "2019-07-21 13:07:41",
    "dnssec": "unsigned",
    "registered": true,
    "status": [
      "clientDeleteProhibited",
      "clientRenewProhibited",
      "clientTransferProhibited",
      "clientUpdateProhibited"
    ],
    "nameservers": [
      "PETE.NS.CLOUDFLARE.COM",
      "MIKI.NS.CLOUDFLARE.COM"
    ],
    "contacts": {
      "owner": [
        {
          "handle": null,
          "type": null,
          "name": null,
          "organization": "HI5 Solutions",
          "email": null,
          "address": null,
          "zipcode": null,
          "city": null,
          "state": "Maharashtra",
          "country": "IN",
          "phone": null,
          "fax": null,
          "created": null,
          "changed": null
        }
      ]
    },
    "registrar": {
      "id": "440",
      "name": "Wild West Domains, LLC",
      "email": "abuse@wildwestdomains.com",
      "url": "http://www.wildwestdomains.com",
      "phone": "+1.4806242505"
    }
  }
}';*/

    $data = json_decode($sampleData, true);

    if ($data['result']['registered'] && !is_null($data['result']['registered'])) {
        $output["name"] = $data["result"]["name"];
        $output["created"] = $data["result"]["created"];
        $output["changed"] = $data["result"]["changed"];
        $output["expires"] = $data["result"]["expires"];
        $output["registered"] = $data["result"]["registered"];
        $output["nameservers"] = $data["result"]["nameservers"];
        $output["contacts"]["owner"]["organization"] = $data["result"]["contacts"]["owner"][0]["organization"];
        $output["contacts"]["owner"]["state"] = $data["result"]["contacts"]["owner"][0]["state"];
        $output["registrar"]["name"] = $data["result"]["registrar"]["name"];
        $output["registrar"]["email"] = $data["result"]["registrar"]["email"];
        $output['error']['message'] = '';
    }

}

// Kal function
function fnCurl($url, $params, $method = "POST", $optionalParams = array())
{

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, false);

    if (!empty($optionalParams['authorization'])) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("authorization:" . $optionalParams['authorization']));
    }

    if (!empty($optionalParams['userPwd'])) {
        curl_setopt($ch, CURLOPT_USERPWD, $optionalParams['userPwd']);
    }

    if (!empty($optionalParams['Content-Type'])) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:" . $optionalParams['Content-Type']));
    }

    if (strtoupper($method) == "GET") {
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
    } elseif (strtoupper($method) == "POST") {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    }

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($ch);

    curl_close($ch);

    if ($result) {
        return $result;
    } else {
        return "";
    }
}

echo json_encode($output);