<?php

/* * ******************************************
 * LiteSpeed LSMCD User Management Plugin for cPanel
 * @Author: LiteSpeed Technologies, Inc. (https://www.litespeedtech.com)
 * @Copyright: (c) 2018
 * ******************************************* */

require_once 'autoloader.php';

use LsmcdUserPanel\Lsmcd_UserMgr_Controller;
use LsmcdUserPanel\CPanelWrapper;
use LsmcdUserPanel\Lsc\UserLogger;
use LsmcdUserPanel\Lsc\UserLSMCDException;

CPanelWrapper::init();

try {
    $app = new Lsmcd_UserMgr_Controller();
    $app->run();
}
catch ( UserLSMCDException $e ) {
    $msg = $e->getMessage();
    UserLogger::logMsg($msg, UserLogger::L_ERROR);

    header($_SERVER["SERVER_PROTCOL"] . " 500 Internal Server Error", 500);
    echo "<h1>LSMCD User Manager Fatal Error</h1>" . "<h2>{$msg}</h2>";
}

