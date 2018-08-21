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
catch ( UserLSMCDException $e ) {
    $msg = $e->getMessage();

    header($_SERVER["SERVER_PROTCOL"] . " 500 Internal Server Error", 500);

    echo <<<HTML
<h2>LSMCD User Manager Fatal Error</h2>
<h3>
  {$msg}
  <br />
  Please contact your hosting provider to address this issue.
  <a href="https://www.litespeedtech.com/support/wiki/doku.php/litespeed_wiki:lsmcd:user-errors"
      target="_blank" rel="noopener">
    (Learn More)
  </a>
</h3>
<button name="do" type="submit" value="main"
    class="uk-button uk-button-muted uk-margin uk-margin-large uk-width-medium-1-10 uk-width-small-1-5"
>
  Back
</button>
HTML;

}

CPanelWrapper::getCpanelObj()->end();
