<?php

/* * ******************************************
 * LiteSpeed LSMCD User Management Plugin for cPanel
 * @author: LiteSpeed Technologies, Inc. (https://www.litespeedtech.com)
 * @copyright: (c) 2018
 * ******************************************* */

namespace LsmcdUserPanel\View\Model;

use LsmcdUserPanel\Lsmcd_UserMgr_Controller;
use LsmcdUserPanel\Lsmcd_UserMgr_Util;
use LsmcdUserPanel\Lsc\UserLogger;
use LsmcdUserPanel\Lsc\UserLSMCDException;
use LsmcdUserPanel\Lsc\Context\UserPanelContextOption;


class StatsModel
{

    const FLD_STATS = 'stats';
    const FLD_USER = 'user';
    const FLD_SERVER = 'server';

    /**
     * @var mixed[]
     */
    private $tplData = array();
    
    /**
     *
     * @param UserControlPanel  $panelEnv
     */
    public function __construct()
    {
        $this->init();
    }

    private function init()
    {
        $this->setStats();
        $this->setUser();
        $this->setServer();
    }

    /**
     *
     * @param string  $feild
     * @return null|mixed
     */
    public function getTplData( $feild )
    {
        if ( !isset($this->tplData[$feild]) ) {
            return null;
        }

        return $this->tplData[$feild];
    }

    private function doStats()
    {
    }
    
    private function setStats()
    {
        $this->tplData[self::FLD_STATS] = $this->doStats();
    }
    
    private function setUser()
    {
        $this->tplData[self::FLD_USER] = 
                Lsmcd_UserMgr_Util::getDataByUser() ?
                    Lsmcd_UserMgr_Util::getCurrentCpanelUser() :
                    '[Whole Server]';
    }
    
    private function setServer()
    {
        $this->tplData[self::FLD_SERVER] = Lsmcd_UserMgr_Util::getServerAddr();
    }

    /**
     *
     * @return string
     */
    public function getTpl()
    {
        return realpath(__DIR__ . '/../Tpl') . '/Stats.tpl';
    }

}
