<?php 

/**
 * @api {post} /api Request Whois information
 * @apiName WhoIsXML
 * @apiGroup Whois
 * @apiHeader (Headers) {String} Authorization Bearer PVXwUKIJm3qEdgh2x2EAz2rR2kS9ynvlLcKQCdttRty4yeU2VGSD5k56zsUM.
 * 
 * @apiParam {String} node whoisxml/jsonwhois/jsonwhoisapi
 * @apiParam {String} domain webgeeks.in
 * @apiParam {String} [r] whois/api/availability (Required If node = jsonwhois)
 * @apiParam {String} [ip_address] (Required If r = ip)
 *
 * @apiSuccessExample {json} Success
 *    HTTP/1.1 200 OK
 *    [{
 *      "status": "Success",
 *      "name": "webgeeks.in",
 *      "created": "2013-01-19T01:03:12Z",
 *      "changed": "2018-10-22T05:36:38Z",
 *      "expires": "2020-01-19T01:03:12Z",
 *      "registered": 1,
 *      "nameservers": [
 *          "NS1.BH-61.WEBHOSTBOX.NET",
 *          "NS2.BH-61.WEBHOSTBOX.NET"
 *      ],
 *      "contacts": {
 *          "owner": {
 *              "organization": "Freelance Web Solution",
 *              "state": "Maharashtra"
 *          }
 *      },
 *      "registrar": {
 *          "name": "Crazy Domains FZ-LLC",
 *          "email": "abuse@registrar.com"
 *      }
 *  }]
 *  @apiErrorExample {json} Domain not found
 *    HTTP/1.1 200 OK
 *    [{
 *      "status": "Error",
 *      "error": {
 *          "message": "Data not found."
 *      }
 *  }]
 * @apiErrorExample {json} Missing a Parameter
 *    HTTP/1.1 206 Partial Content
 *    [{
 *      "status":"error",
 *      "message":"Fella !! You might forgot something to bring here.",
 *      "data":""
 *    }]
 * @apiErrorExample {json} Server Error
 *    HTTP/1.1 500 Internal Server Error
 * @apiErrorExample {json} Route Not Found
 *    HTTP/1.1 404 Not Found
 */

require_once(realpath(dirname(__FILE__) . '/config/constants.php'));
require_once(realpath(dirname(__FILE__) . '/config/reusables.php'));

if(isset($_POST['node']) && $_POST['node'] != '') {
    $node = strtolower(trim($_POST['node']));
    if($node == 'whoisxml') { // 500 Requests - domain/ip/availability (2nd Priority)
        require_once(realpath(dirname(__FILE__) . '/sources/whoisxmlapi.php'));
    } elseif($node == 'jsonwhois') { // 250 Requests - domain/ip, 500 Requests - availability (3rd Priority)
        require_once(realpath(dirname(__FILE__) . '/sources/jsonwhois.php'));
    } elseif($node == 'jsonwhoisapi') { // 1000 Requests Per Month (1st Priority)
        require_once(realpath(dirname(__FILE__) . '/sources/jsonwhoisapi.php'));
    } else {
        $respArr['status'] = 'error';
        $respArr['data'] = '';
        $respArr['message'] = 'Fella !! You might forgot something to bring here.';
        echoResponse($respArr);
    }
} else {
    $respArr['status'] = 'error';
    $respArr['data'] = '';
    $respArr['message'] = 'Fella !! You might forgot something to bring here.';
    echoResponse($respArr);
}