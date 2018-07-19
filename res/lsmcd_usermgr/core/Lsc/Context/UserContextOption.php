<?php

/* * ******************************************
 * LiteSpeed LSMCD User Management Plugin for cPanel
 * @Author: LiteSpeed Technologies, Inc. (https://www.litespeedtech.com)
 * @Copyright: (c) 2018
 * ******************************************* */

namespace LsmcdUserPanel\Lsc\Context;

use \LsmcdUserPanel\Lsc\UserUtil;
use LsmcdUserPanel\CPanelWrapper;

class UserContextOption
{

    const FROM_CONTROL_PANEL = 'panel';

    /**
     * @var string
     */
    protected $invokerName;

    /**
     * @var string
     */
    protected $invokerType;

    /**
     * @var string  If set, must be writable.
     */
    protected $logFile;

    /**
     * @var int  Log to file level.
     */
    protected $logFileLvl;

    /**
     * @var int  Echo to user interface level.
     */
    protected $logEchoLvl;

    /**
     * @var boolean
     */
    protected $bufferedWrite;

    /**
     * @var boolean
     */
    protected $bufferedEcho;

    /**
     * @var int
     */
    protected $scanDepth = 2;

    /**
     *
     * @param string   $invokerName
     * @param string   $invokerType
     * @param int      $logFileLvl
     * @param int      $logEchoLvl
     * @param boolean  $bufferedWrite
     * @param boolean  $bufferedEcho
     */
    protected function __construct( $invokerName, $invokerType,
            $logFileLvl, $logEchoLvl, $bufferedWrite, $bufferedEcho )
    {
        $this->invokerName = $invokerName;
        $this->invokerType = $invokerType;
        $this->logFile = 'lsmcd_usermgr.log';
        $this->logFileLvl = $logFileLvl;
        $this->logEchoLvl = $logEchoLvl;
        $this->bufferedWrite = $bufferedWrite;
        $this->bufferedEcho = $bufferedEcho;
    }

    /**
     *
     * @return string
     */
    public function getLogFile()
    {
        return $this->logFile;
    }

    /**
     *
     * @return int
     */
    public function getLogFileLvl()
    {
        return $this->logFileLvl;
    }

    /**
     *
     * @return int
     */
    public function getLogEchoLvl()
    {
        return $this->logEchoLvl;
    }

    /**
     *
     * @return boolean
     */
    public function isBufferedWrite()
    {
        return $this->bufferedWrite;
    }

    /**
     *
     * @return boolean
     */
    public function isBufferedEcho()
    {
        return $this->bufferedEcho;
    }

    /**
     *
     * @return int
     */
    public function getScanDepth()
    {
        return $this->scanDepth;
    }

    /**
     *
     * @return string
     */
    public function getInvokerType()
    {
        return $this->invokerType;
    }

    /**
     *
     * @return string
     */
    public function getInvokerName()
    {
        return $this->invokerName;
    }

}
