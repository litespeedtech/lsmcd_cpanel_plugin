<?php

/* * ******************************************
 * LiteSpeed LSMCD User Management Plugin for cPanel
 * @Author: LiteSpeed Technologies, Inc. (https://www.litespeedtech.com)
 * @Copyright: (c) 2018
 * ******************************************* */

namespace LsmcdUserPanel;

class CPanelWrapper
{

    /**
     * @var null|\CPANEL
     */
    private static $cpanel;

    /**
     * Used as a wrapper, no need to construct an object of this class.
     * Chosen over __call() magic method implementation (possible unexpected
     * behavior).
     */
    private function __construct() {}

    public static function init()
    {
        require_once '/usr/local/cpanel/php/cpanel.php';
        self::$cpanel = new \CPANEL();
    }

    public static function getCpanelObj()
    {
        if ( !self::$cpanel ) {
            self::init();
        }

        return self::$cpanel;
    }

}
