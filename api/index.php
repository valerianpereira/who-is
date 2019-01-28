<?php 

require_once(realpath(dirname(__FILE__) . '/config/constants.php'));
require_once(realpath(dirname(__FILE__) . '/config/reusables.php'));

if(isset($_POST['node']) && $_POST['node'] != '') {
    $node = strtolower(trim($_POST['node']));
    if($node == 'whoisxml') { // 500 Requests - domain/ip/availability (2nd Priority)
        require_once(realpath(dirname(__FILE__) . '/sources/whoisxmlapi.php'));
    } elseif($node == 'jsonwhois') { // 1000 Requests Per Month (1st Priority)
        require_once(realpath(dirname(__FILE__) . '/sources/jsonwhois.php'));
    } elseif($node == 'jsonwhoisapi') { // 250 Requests - domain/ip, 500 Requests - availability (3rd Priority)
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