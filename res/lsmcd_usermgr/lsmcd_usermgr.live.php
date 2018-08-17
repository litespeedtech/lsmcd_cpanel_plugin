<?php

/* * ******************************************
 * LiteSpeed LSMCD User Manager Plugin for cPanel
 * @Author:   LiteSpeed Technologies, Inc. (https://www.litespeedtech.com)
 * @Copyright: (c) 2018
 * ******************************************* */

/**
 * This file is currently called from a Perl process using an Ajax request.
 * It cannot be executed directly from Perl as a cPanel LiveAPI connection to
 * cpsvrd (private socket to API engine) cannot be made unless the file is
 * served through cpsvrd.
 */

$activeTheme = 'default';

include "landing/{$activeTheme}/index.php";
