<?php

/* * ******************************************
 * LiteSpeed LSMCD User Management Plugin for cPanel
 * @author:   LiteSpeed Technologies, Inc. (https://www.litespeedtech.com)
 * @copyright: (c) 2018
 * ******************************************* */

namespace LsmcdUserPanel\Lsc\Panel;

use LsmcdUserPanel\Lsmcd_UserMgr_Util;
use LsmcdUserPanel\Lsc\UserLogger;
use LsmcdUserPanel\Lsc\UserLogEntry;

abstract class UserControlPanel
{

    const PHP_TIMEOUT = 10;

    /**
     * @var string
     */
    protected $phpOptions;

    /**
     * @var null|mixed[][]  'docroots' => (index => docroots),
     *                      'names' => (servername => index)
     */
    protected $docRootMap = null;

    /**
     * @var null|UserControlPanel
     */
    protected static $instance;

    protected function __construct()
    {
        $this->phpOptions = '-d disable_functions=ini_set '
                . '-d max_execution_time=' . self::PHP_TIMEOUT 
                . ' -d memory_limit=512M '
                . '-d register_argc_argv=1 -d zlib.output_compression=0';
    }

    /**
     *
     * @param string  $name
     * @throws UserLSCMException
     */
    public static function init( $name )
    {
        switch ($name) {
            case 'cpanel_user':
                self::$instance = new UserCPanel();
                break;
            default:
                throw new UserLSMCDException("Control panel '{$name}' "
                    . "is not supported.");
        }
    }

    /**
     *
     * @return UserCPanel
     * @throws UserLSMCDException
     */
    public static function getInstance()
    {
        if ( self::$instance == null ) {
            throw new UserLSMCDException('Could not get instance, ControlPanel'
                    . ' not initialized. ');
        }

        return self::$instance;
    }

    /**
     *
     * @param string  $msg
     * @param int     $lvl
     */
    protected function log( $msg, $lvl )
    {
        UserLogger::logMsg("{$this->panelName} {$msg}", $lvl);
    }

}
