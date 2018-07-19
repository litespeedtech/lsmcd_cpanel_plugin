<?php

/* * *********************************************
 * LiteSpeed LSMCD User Management Plugin for cPanel
 * @Author: LiteSpeed Technologies, Inc. (https://www.litespeedtech.com)
 * @Copyright: (c) 2018
 * *******************************************
 */

use \LsmcdUserPanel\Lsc\Context\UserContext;
use \LsmcdUserPanel\Lsc\Context\UserPanelContextOption;

UserContext::initialize(new UserPanelContextOption('cpanel_user'));
