<?php

/* * ******************************************
 * LiteSpeed Web Cache Management Plugin for cPanel
 * @author:   LiteSpeed Technologies, Inc. (https://www.litespeedtech.com)
 * @copyright: (c) 2018
 * ******************************************* */

namespace LsUserPanel\Lsc\Panel;

class UserCPanel extends UserControlPanel
{

    protected function __construct()
    {
        $this->panelName = 'cPanel/WHM';

        parent::__construct();
    }

}
