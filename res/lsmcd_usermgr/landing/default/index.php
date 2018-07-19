<?php

/* * ******************************************
 * LiteSpeed LSMCD User Management Plugin for cPanel
 * @Author: LiteSpeed Technologies, Inc. (https://www.litespeedtech.com)
 * @Copyright: (c) 2018
 * ******************************************* */

require_once 'autoloader.php';

use LsmcdUserPanel\Lsmcd_UserMgr_Controller;
use LsmcdUserPanel\CPanelWrapper;
use LsmcdUserPanel\Lsc\UserLSMCDException;

CPanelWrapper::init();

try {
    $app = new Lsmcd_UserMgr_Controller();
    $app->run();
}
catch (UserLSMCDException $e ) {
    $msg = $e->getMessage();
    //Logger::logMsg($msg, Logger::L_ERROR);

    header($_SERVER["SERVER_PROTCOL"] . " 500 Internal Server Error", 500);
    echo "<h1>LSMCD User Manager Fatal Error</h1>" . "<h2>{$msg}</h2>";
}
/*
$post = print_r($_POST, TRUE);
$request = print_r($_REQUEST, TRUE);
$get = print_r($_GET, TRUE);
$server = print_r($_SERVER, TRUE);
$session = print_r($_SESSION, TRUE);
$env = print_r($_ENV, TRUE);

    echo "<h1>LSMCD User Manager Fatal Error</h1>" 
            . "<pre>      Exiting program, POST: " 
            . $post . '      REQUEST: ' . $request . '      GET: ' 
            . $get . '      SERVER: ' . $server  . '      SESSION: ' . $session
            . '      ENV: ' . $env . '</pre>';
*/