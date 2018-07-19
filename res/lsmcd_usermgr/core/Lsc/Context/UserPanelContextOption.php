<?php

/* * ******************************************
 * LiteSpeed LSMCD User Management Plugin for cPanel
 * @Author:   LiteSpeed Technologies, Inc. (https://www.litespeedtech.com)
 * @Copyright: (c) 2018
 * ******************************************* */

namespace LsmcdUserPanel\Lsc\Context;

use \LsmcdUserPanel\Lsc\UserLogger;

class UserPanelContextOption extends UserContextOption
{

    /**
     * @var string
     */
    protected $iconDir;

    /**
     * @var string
     */
    protected $sharedTplDir = __DIR__ . '/../View/Tpl';

    /**
     *
     * @param string  $panelName
     */
    public function __construct( $panelName )
    {
        $invokerName = $panelName;
        $invokerType = parent::FROM_CONTROL_PANEL;

        $logFileLvl = UserLogger::L_DEBUG;
        $logEchoLvl = UserLogger::L_DEBUG;
        $bufferedWrite = true;
        $bufferedEcho = true;

        parent::__construct($invokerName, $invokerType, $logFileLvl,
                $logEchoLvl, $bufferedWrite, $bufferedEcho);

        $this->init();
    }

    private function init()
    {
        $this->scanDepth = 2;
    }

    /**
     *
     * @param string  $iconDir
     */
    public function setIconDir( $iconDir )
    {
        $this->iconDir = $iconDir;
    }

    /**
     *
     * @return string
     */
    public function getIconDir()
    {
        return $this->iconDir;
    }

}
